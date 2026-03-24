<?php
namespace modules\crmmodule;

use Craft;
use craft\base\Module;
use craft\events\RegisterUrlRulesEvent;
use craft\web\UrlManager;
use yii\base\Event;

/**
 * Custom CRM Module for handling form routing, user preferences, and custom logic.
 */
class CrmModule extends Module
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        // Set the alias for the module
        Craft::setAlias('@modules/crmmodule', $this->getBasePath());

        // Set the controller namespace
        $this->controllerNamespace = 'modules\crmmodule\controllers';

        parent::init();

        // Register custom site routes
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                // Route for HTMX form fetching
                $event->rules['api/get-form'] = 'crmmodule/form/get-form';
                
                // Add more custom routes here...
            }
        );

        // Register future event hooks
        // e.g. EVENT_BEFORE_SAVE for entry modifications
        // Event::on(Entry::class, Entry::EVENT_BEFORE_SAVE, function(ModelEvent $event) { ... });

        Craft::info(
            'CrmModule module loaded',
            __METHOD__
        );
    }
}
