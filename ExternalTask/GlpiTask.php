<?php

namespace Kanboard\Plugin\Glpi\ExternalTask;

use Kanboard\Core\ExternalTask\ExternalTaskInterface;

class GlpiTask implements ExternalTaskInterface
{
    protected $uri;
    protected $ticket;

    public function __construct($uri, $ticket)
    {
        $this->uri = $uri;
        $this->ticket = $ticket;
    }

    /**
     * Return Uniform Resource Identifier for the task
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Return the ticket id
     *
     * @return int
     */
    public function getTicketId()
    {
        return $this->ticket['id'];
    }

    /**
     * Return the ticket details
     *
     * @return array
     */
    public function getTicket()
    {
        return $this->ticket;
    }

    /**
     * Return a dict to populate the task form
     *
     * @return array
     */
    public function getFormValues()
    {
        $title = sprintf('GLPI %d %s', $this->ticket['id'], $this->ticket['name']);
        return array(
            'title' => $title,
            'description' => $this->ticket['content'],
            'reference' => $this->ticket['id'],
            'priority' => $this->ticket['priority'],
        );
    }

}
