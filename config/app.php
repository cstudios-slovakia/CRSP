<?php
/**
 * Yii Application Config
 */

use craft\helpers\App;

return [
    'id' => App::env('CRAFT_APP_ID') ?: 'CraftCMS',
    'modules' => [
        'crmmodule' => \modules\crmmodule\CrmModule::class,
    ],
    'bootstrap' => ['crmmodule'],
];
