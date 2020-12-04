<?php


use yii\console\controllers\MigrateController;
use zetsoft\system\conapp\CallerModule;
use zetsoft\system\conapp\CoresModule;
use zetsoft\system\conapp\CrudsModule;
use zetsoft\system\conapp\TesterModule;

$config = [

    'defaultRoute' => 'help',

    'bootstrap' => [
    ],

    'params' => [
    ],

    'controllerMap' => [
        'migrate' => [
            'class' => MigrateController::class,
            'migrationPath' => null,
            'useTablePrefix' => true,
            'db' => 'db',
            'migrationTable' => 'core_migra',
            'migrationNamespaces' => [
                'zetsoft\migrate\ALL',
            ],
        ],
    ],

    'layout' => false,

    'enableCoreCommands' => true,

    'components' => [

        'urlManager' => [
            'baseUrl' => ''
        ]


    ],
    'modules' => [




        'gridview' => [
            'class' => kartik\grid\Module::class,
            'downloadAction' => '/gridview/export/download',
        ],

        'caller' => [
            'class' => CallerModule::class
        ],
        
        'cores' => [
            'class' => CoresModule::class
        ],


        'cruds' => [
            'class' => CrudsModule::class
        ],

        'tester' => [
            'class' => TesterModule::class
        ],
    ]
];


return $config;


