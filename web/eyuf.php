<?php

/**
 *
 * Maintenance
 */

use zetsoft\system\Az;

$config = [

    'catchAll' => null,
    'controllerMap' => [



    ],
    'bootstrap' => [],
    'timeZone' => 'Asia/Tashkent',
    'components' => [
        'formatter' => [
            'dateFormat' => 'd.MM.yyyy',
            'timeFormat' => 'H:mm:ss',
            'datetimeFormat' => 'd.MM.yyyy H:mm',
        ],
    ],
    'modules' => [


        'filepond' => [
            'class' => \vkabachenko\filepond\Module::class
        ]
    ],
    'on beforeAction' => function ($event) {
    },
];

return $config;


