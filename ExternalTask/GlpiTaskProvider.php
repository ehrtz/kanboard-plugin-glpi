<?php

namespace Kanboard\Plugin\Glpi\ExternalTask;

use Kanboard\Core\Base;
use Kanboard\Core\ExternalTask\ExternalTaskProviderInterface;
use Kanboard\Core\ExternalTask\NotFoundException;

class GlpiTaskProvider extends Base implements ExternalTaskProviderInterface
{
    private $session_token = '';
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
        $this->session_token = $this->getGlpiSession();

        if ($this->session_token == ''){
            throw new NotFoundException("Coudn't fetch GLPI ticket data! Authentication Error!");
        }

        $ticket = $this->getGlpiTicket($uri);

        if (isset($ticket) && empty($ticket['id'])) {
            throw new NotFoundException($ticket[1]);
        }

        if (! empty($ticket)){
            $ticket['actor'] = $this->getGlpiTicketActor($uri);
        }
        #echo '<pre>';
        #var_dump($ticket);
        #echo '</pre>';
        $this->killGlpiSession();
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

        if (isset($token['session_token'])){
            return $token['session_token'];
        }

        return '';
    }

    protected function killGlpiSession()
    {
        $headers = array(
            'session-token: ' . $this->session_token
        );

        $url = $this->configModel->get('glpi_url') . '/apirest.php/killSession/';
        $token = $this->httpClient->getJson($url, $headers);
        return true;
    }

    protected function getAuthorizationHeaders(){
        $headers = array();
        $app_token = $this->configModel->get('glpi_app_token');

        if (!empty($app_token)) {
            $headers[] = 'App-Token: '. $app_token;
        }

        #TODO: apply condition whether user credentials or user token is used
        #      for API authentication
        $user_token = $this->configModel->get('glpi_user_token');

        if (!empty($user_token)) {
           $headers[] = 'Authorization: user_token '. $user_token;
        }

        return $headers;
    }

    protected function getGlpiTicket($uri)
    {
        $headers = array(
            'session-token: ' . $this->session_token
        );

        $app_token = $this->configModel->get('glpi_app_token');

        if (!empty($app_token)) {
            $headers[] = 'App-Token: '. $app_token;
        }

        $matches = array();
        if (preg_match('/id=(\d+)$/', $uri, $matches)) {
            $id = $matches[1];
        }

        $ticket_path = '/apirest.php/Ticket/' . $id . '?expand_dropdowns=true&get_hateoas=false';
        return $this->httpClient->getJson($this->getBaseUrl() . $ticket_path, $headers);
    }

    protected function getGlpiTicketActor($uri)
    {
        $matches = array();
        if (preg_match('/id=(\d+)$/', $uri, $matches)) {
            $id = $matches[1];
        }

        $app_token = $this->configModel->get('glpi_app_token');

        if (!empty($app_token)) {
            $headers[] = 'App-Token: '. $app_token;
        }

        $ticket_path = '/apirest.php/Ticket/' . $id . '/Ticket_User' . '?expand_dropdowns=true&get_hateoas=false';
        return $this->httpClient->getJson($this->getBaseUrl() . $ticket_path, $headers);
    }
}
