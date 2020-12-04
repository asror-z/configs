<?php

use yii\authclient\Collection;
use yii\helpers\BaseFileHelper;
use yii\web\DbSession;
use zetsoft\webs\apps\ZWebAction;
use zetsoft\dbdata\web\ActionWebData;
use zetsoft\dbitem\core\WebItem;
use zetsoft\models\user\User;
use zetsoft\module\rest\ApiModule;
use zetsoft\module\rest\RestModule;
use zetsoft\module\rest\TerrabaytModule;
use zetsoft\module\web\AdminModule;
use zetsoft\module\web\KernelModule;
use zetsoft\module\web\TesterModule;
use zetsoft\system\assets\ZAssetManager;
use zetsoft\system\Az;
use zetsoft\system\control\ZRequest;
use zetsoft\system\except\ZErrorHandler;
use zetsoft\system\helpers\ZArrayHelper;
use zetsoft\system\helpers\ZUrl;
use zetsoft\system\kernels\ZView;
use zetsoft\system\module\ZDebug;
use zetsoft\system\module\ZUserPanel;
use zetsoft\system\targets\ZDbSession;


$config = [

    'aliases' => [],

    'controller' => null,
    'viewPath' => null,
    'defaultRoute' => null,

    'homeUrl' => $boot->env('urlHome'),
    'timeZone' => 'Asia/Tashkent',
    'components' => [


        //  yii\web\ErrorHandler
        'errorHandler' => [
            'class' => ZErrorHandler::class,
            'errorAction' => '/core/core/error',

            'exceptionView' => Root.'/webhtm/core/error/exception.php',
            'callStackItemView' => '/webhtm/core/error/stackItem.php',
            'previousExceptionView' => '/webhtm/core/error/previous.php',

            'maxSourceLines' => 100,
            'maxTraceSourceLines' => 100,
        ],

        //  yii\web\AssetManager
        'assetManager' => [
            'class' => ZAssetManager::class,

            'afterCopy' => null,
            'beforeCopy' => null,
            'hashCallback' => null,

            'assetMap' => [],
            'bundles' => [],

            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',

            'dirMode' => 0755,
            'fileMode' => null,

            'appendTimestamp' => false,
            'forceCopy' => false,
            'linkAssets' => false,
        ],

        //  yii\web\Request
        'request' => [
            'class' => ZRequest::class,
            'enableCookieValidation' => true,
            'enableCsrfCookie' => true,
            'enableCsrfValidation' => true,

            'csrfParam' => '_csrf',
            'methodParam' => '_method',

            'csrfCookie' => ['httpOnly' => true],

            'cookieValidationKey' => $boot->env('appTitle') . $boot->env('appVersion') . '_hQtvUtJQsfsfstseXS5',

            'parsers' => [
                'application/json' => yii\web\JsonParser::class,
            ],

        ],


    ],

    #region Requests
    'on beforeRequest' => function ($event) {

    },
    'on afterRequest' => function ($event) {

    },
    #endregion
    #region Actions
    'on beforeAction' => function ($event) {
    },

    'on afterAction' => function ($event) {

    },
    #endregion

    'modules' => [

        /**
         *
         * Common modules
         */

        /**
         *
         * System Modules
         */

        'datecontrol' => [
            'class' => kartik\datecontrol\Module::class,
            'displaySettings' => [
                'date' => 'd-m-Y',
                'time' => 'H:i:s A',
                'datetime' => 'd-m-Y H:i:s A',
            ],

            // format settings for saving each date attribute
            'saveSettings' => [
                'date' => 'Y-m-d',
                'time' => 'H:i:s',
                'datetime' => 'Y-m-d H:i:s',
            ],

            'autoWidget' => true,
        ],

        'filepond' => [
            'class' => \vkabachenko\filepond\Module::class
        ],

        'treemanager' => [
            'class' => kartik\tree\Module::class,
//            'treeViewSettings'=> [
//                'nodeView' => '@kvtree/views/_form'
//            ]
            // see settings on http://demos.krajee.com/tree-manager#module
        ],

        'gridview' => [
            'class' => kartik\grid\Module::class,
            'downloadAction' => '/gridview/export/download',
        ],


    ],
];
 
if ($boot->enableDebug()) {


    $path = Root. '/storing/runtime/web/' . App . '/debug';
    
    if (!file_exists($path))
        BaseFileHelper::createDirectory($path);

    $config['bootstrap'][] = 'debug';

    $config['modules']['debug'] = [
        'class' => yii\debug\Module::class,
        'enableDebugLogs' => false,
        'allowedIPs' => ['*'],
        'dataPath' => '@runtime/debug',
        'allowedHosts' => ['*'],
        'checkAccessCallback' => null,
        //'controllerNamespace' => '',
        'defaultHeight' => 50,
        'defaultPanel' => 'log',
        'dirMode' => 0775,
        'disableCallbackRestrictionWarning' => false,
        'disableIpRestrictionWarning' => false,
        'fileMode' => null,
        'historySize' => 50,
        'logTarget' => null,
        'pageTitle' => '',
        'panels' => [
            'config' => ['class' => 'yii\debug\panels\ConfigPanel'],
            'request' => ['class' => 'yii\debug\panels\RequestPanel'],
            'router' => ['class' => 'yii\debug\panels\RouterPanel'],
            'log' => ['class' => 'yii\debug\panels\LogPanel'],
            'profiling' => ['class' => 'yii\debug\panels\ProfilingPanel'],
            'db' => ['class' => 'yii\debug\panels\DbPanel'],
            'event' => ['class' => 'yii\debug\panels\EventPanel'],
            'assets' => ['class' => 'yii\debug\panels\AssetPanel'],
            'mail' => ['class' => 'yii\debug\panels\MailPanel'],
            'timeline' => ['class' => 'yii\debug\panels\TimelinePanel'],
            'user' => ['class' => ZUserPanel::class],
            'dump' => ['class' => 'yii\debug\panels\DumpPanel'],
        ],

        //'traceLine' => null,
        'tracePathMappings' => [],
    ];

}


return $config;


