<?php

use craft\helpers\App;

return [
    'components' => [
        // See -> https://github.com/yiisoft/yii2-redis/blob/master/docs/guide/topics-session.md
        // 'session' => function() {
        //     // Get the default component config
        //     $config = App::sessionConfig();
        //     // Override the class to use 'Redis' session class
        //     $config['class'] = yii\redis\Session::class;
        //     // Set prefix for each record
        //     $config['keyPrefix'] = App::env('CRAFT_APP_ID') ?: 'Craft';
        //     // Instantiate and return it
        //     return Craft::createObject($config);
        // },
    ],
];
