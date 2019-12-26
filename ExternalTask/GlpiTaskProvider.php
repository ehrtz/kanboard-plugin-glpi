<?php

namespace Kanboard\Plugin\Glpi\ExternalTask;

use Kanboard\Core\Base;
use Kanboard\Core\ExternalTask\ExternalTaskProviderInterface;
use Kanboard\Core\ExternalTask\NotFoundException;

class GlpiTaskProvider extends Base implements ExternalTaskProviderInterface
{
    /**
     * Get provider name (visible in the user interface)
     *
     * @access public
     * @return string
     */
    public function getName()
    {
        return 'Glpi';
    }

    /**
     * Get provider icon
     *
     * @access public
     * @return string
     */
    public function getIcon()
    {
        return '<i class="fa fa-sticky-note"></i>';
    }

    /**
     * Get label for adding a new task
     *
     * @access public
     * @return string
     */
    public function getMenuAddLabel()
    {
        return t('Add GLPI Ticket');
    }

    /**
     * Retrieve task from external system or cache
     *
     * @access public
     * @throws \Kanboard\Core\ExternalTask\ExternalTaskException
     * @param  string $uri
     * @param  int    $projectID
     * @return ExternalTaskInterface
     */
    public function fetch($uri, $projectID)
    {
        $ticket = $this->getGlpiTicket($uri);
        return new GlpiTask($uri, $ticket);
    }

    /**
     * Save external task to another system
     *
     * @throws \Kanboard\Core\ExternalTask\ExternalTaskException
     * @param  string $uri
     * @param  array  $formValues
     * @param  array  $formErrors
     * @return bool
     */
    public function save($uri, array $formValues, array &$formErrors)
    {
        return true;
    }

    /**
     * Get task import template name
     *
     * @return string
     */
    public function getImportFormTemplate()
    {
        return 'Glpi:task/import';
    }

    /**
     * Get creation form template
     *
     * @return string
     */
    public function getCreationFormTemplate()
    {
        return 'Glpi:task/creation';
    }

    /**
     * Get modification form template
     *
     * @return string
     */
    public function getModificationFormTemplate()
    {
        return 'Glpi:task/modification';
    }

    /**
     * Get task view template name
     *
     * @return string
     */
    public function getViewTemplate()
    {
        return 'Glpi:task/view';
    }

    /**
     * Build external task URI based on import form values
     *
     * @param  array $formValues
     * @return string
     */
    public function buildTaskUri(array $formValues)
    {
        ///front/ticket.form.php?id=6
        return $this->getBaseUrl() . '/front/ticket.form.php?id=' . $formValues['id'];
    }

    protected function getBaseUrl()
    {
        return $this->configModel->get('glpi_url');
    }

    protected function getGlpiSession()
    {
        $url = $this->configModel->get('glpi_url') . '/apirest.php/initSession/';
        $token = $this->httpClient->getJson($url, $this->getAuthorizationHeaders());
        return $token['session_token'];
    }

    protected function getAuthorizationHeaders(){
        $user_token = $this->configModel->get('glpi_user_token');

        if (!empty($user_token)) {
            return array(
                'Authorization: user_token '. $user_token
            );
        }
    }

    protected function getGlpiTicket($uri)
    {
        $headers = array(
            'session-token: ' . $this->getGlpiSession() //npdft94t1i2d1iuijbusuuf87g
        );

        $matches = array();
        if (preg_match('/id=(\d+)$/', $uri, $matches)) {
            $id = $matches[1];
        }

        $ticket_path = '/apirest.php/Ticket/' . $id;
        return $this->httpClient->getJson($this->getBaseUrl() . $ticket_path, $headers);
    }
}
