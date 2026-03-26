<?php
namespace modules\crmmodule\controllers;

use Craft;
use craft\web\Controller;
use craft\elements\Entry;

/**
 * Handles form routing and HTMX endpoints for the CRM module.
 */
class FormController extends Controller
{
    /**
     * @var int|bool|array Allows anonymous access to this controller's actions.
     */
    protected array|int|bool $allowAnonymous = ['get-form'];

    /**
     * Renders a form layout for a given entry via HTMX.
     * Route: /api/get-form (defined in CrmModule.php)
     *
     * @return \yii\web\Response | string
     */
    public function actionGetForm()
    {
        $request = Craft::$app->getRequest();
        
        $entryId = $request->getParam('entryId');

        if (!$entryId) {
            Craft::$app->getResponse()->setStatusCode(400);
            return '<div class="alert alert-warning">No entry ID provided to /api/get-form.</div>';
        }

        // Fetch the entry, regardless of status (so we can edit drafts/disabled entries if needed)
        $entry = Entry::find()->id($entryId)->status(null)->one();

        if (!$entry) {
            Craft::$app->getResponse()->setStatusCode(404);
            return '<div class="alert alert-danger">Entry not found.</div>';
        }

        // Render a dynamic string template that imports our dispatcher component
        $template = <<<TWIG
{% import "_components/dispatcher" as dispatcher %}
{{ dispatcher.renderLayout(entry) }}
TWIG;

        try {
            return $this->renderString($template, [
                'entry' => $entry
            ]);
        } catch (\Exception $e) {
            Craft::error('Error rendering form layout: ' . $e->getMessage(), __METHOD__);
            Craft::$app->getResponse()->setStatusCode(500);
            return '<div class="alert alert-danger">Error rendering form layout. See latest logs.</div>';
        }
    }
}
