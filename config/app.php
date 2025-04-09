<?php

use craft\helpers\App;

return [
    'id' => App::env('CRAFT_APP_ID') ?: 'CraftCMS',
    'components' => [
        'view' => function() {
            return Craft::createObject(App::viewConfig() + [
                'on registerSiteTemplateRoots' => function($event) {
                    $event->roots['_root'] = Craft::getAlias('@root');
                },
            ]);
        },
    ]
];
