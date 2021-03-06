<?php
namespace Kanboard\Plugin\Glpi;

use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Translator;
use Kanboard\Model\TaskModel;
use Kanboard\Plugin\Glpi\ExternalTask\GlpiTaskProvider;
use Kanboard\Plugin\Glpi\Subscriber\GlpiSubscriber;
use Kanboard\Plugin\Glpi\Action\CheckAction;
use Kanboard\Core\ExternalTask\ExternalTaskException;

class Plugin extends Base
{
    public function initialize()
    {
        $this->template->hook->attach('template:config:integrations', 'Glpi:config/integration');

        $provider = new GlpiTaskProvider($this->container);
        $this->externalTaskManager->register($provider);

        $subscriber = new GlpiSubscriber($this->container);
        $this->dispatcher->addSubscriber($subscriber);

        $action = new CheckAction($this->container);
        $this->actionManager->register($action);

        $this->hook->on('model:task:creation:prepare', function($values){

            if (isset($values['reference']) && !empty($values['reference'])){
                $tasks = $this->taskFinderModel->getByReference($values['project_id'], $values['reference']);

                if (isset($tasks)) {
                    throw new ExternalTaskException('External Task already exist in current project with Task ID : ' . $tasks['id']);
                }
            }
        });
    }

    /**
     * Get Plugin Name
     *
     * @return string
     */
    public function getPluginName()
    {
        return 'Glpi';
    }

    /**
     * Get Plugin Description
     *
     * @return string
     */
    public function getPluginDescription()
    {
        return t('Integration with GLPI');
    }

    /**
     * Get Plugin Author
     *
     * @return string
     */
    public function getPluginAuthor()
    {
        return 'Ferdinand Canta';
    }

    /**
     * Get Plugin Version
     *
     * @return string
     */
    public function getPluginVersion()
    {
        return '1.0.0';
    }

    /**
     * Get Plugin Homepage
     *
     * @return string
     */
    public function getPluginHomepage()
    {
        return 'https://github.com/ehrtz/kanboard-plugin-glpi';
    }
}
