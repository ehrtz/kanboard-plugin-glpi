<?php

namespace Kanboard\Plugin\Glpi\Action;

use Kanboard\Action\Base;
use Kanboard\Model\TaskModel;
use Kanboard\Plugin\Glpi\ExternalTask\GlpiTask;

class CheckAction extends Base
{
    public function getDescription()
    {
        return 'Check Glpi Ticket for changes';
    }
    public function doAction(array $data)
    {
        $tasks = $this->taskFinderModel->getAll($this->getProjectId());
        $tasks = array_filter($tasks, function($task) {
            return $task['external_provider'] == 'Glpi';
        });
        $provider = $this->externalTaskManager->getProvider('Glpi');
        foreach ($tasks as $task) {
            $t = $provider->fetch($task['external_uri'], $task['project_id']);
            $last_sync = $this->taskMetadataModel->get($task['id'], 'glpi_last_sync', '0');
            $ticket = $t->getTicket();
            $last_updated = date('d-m-Y H:i:s', strtotime($ticket['date_mod']));

            if ($last_updated > $last_sync) {
                $values = array();

                $title = sprintf('GLPI %d %s', $ticket['id'], $ticket['name']);
                if ($task['title'] != $title) {
                    $values += array(
                        'title' => $title,
                    );
                }

                if ($task['description'] != $ticket['content']) {
                    $values += array(
                        'description' => $ticket['content'],
                    );
                }

                if ($task['priority'] != $ticket['priority']) {
                    $values += array(
                        'priority' => $ticket['priority'],
                    );
                }

                if ( $ticket['status'] == GlpiTask::CLOSED || $ticket['status'] == GlpiTask::SOLVED ) {
                    $values += array(
                        'is_active' => TaskModel::STATUS_CLOSED,
                    );
                }

                if (isset($values) && !empty($values)) {
                    $values += array(
                        'id' => $task['id'],
                    );

                    $this->taskStatusModel->close($task['id']);
                    $taskEventJob = $this->taskEventJob->withParams($task['id'], array(TaskModel::EVENT_UPDATE));
                    $this->queueManager->push($taskEventJob);

                    # use GLPI ticket last modification data/time
                    # Kanboard and GLPI might have different timezone setup
                    # so using Kanboard time, synch won't work properly
                    $this->taskMetadataModel->save($task['id'], array(
                        'glpi_last_sync' => $last_updated,
                    ));
                    $this->logger->info(sprintf('Glpi ticket %d was updated (task #%d)', $task['reference'], $task['id']));
                }
            }
        }
    }

    public function getActionRequiredParameters()
    {
        return array();
    }

    public function getEventRequiredParameters()
    {
        return array();
    }

    public function getCompatibleEvents()
    {
        return array('task.cronjob.daily');
    }

    public function hasRequiredCondition(array $data)
    {
        return true;
    }
}
