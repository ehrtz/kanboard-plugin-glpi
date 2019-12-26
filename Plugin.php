<?php
namespace Kanboard\Plugin\Glpi;

use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Translator;
use Kanboard\Model\TaskModel;
use Kanboard\Plugin\Glpi\ExternalTask\GlpiTaskProvider;
use Kanboard\Plugin\Glpi\Subscriber\GlpiSubscriber;

class Plugin extends Base
{
    public function initialize()
    {
        $this->template->hook->attach('template:config:integrations', 'Glpi:config/integration');

        $provider = new GlpiTaskProvider($this->container);
        $this->externalTaskManager->register($provider);

        $subscriber = new GlpiSubscriber($this->container);
        $this->dispatcher->addSubscriber($subscriber);
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
        return '0.1.0';
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
