<?php

use yii\rbac\DbManager;
use yii\swiftmailer\Mailer;
use zetsoft\service\utility\Monolog;
use zetsoft\system\except\ZErrorHandler;
use zetsoft\system\helpers\ZFormatter;
use zetsoft\system\kernels\ZView;
use zetsoft\system\targets\ZTelegram;


/** @var Boot $boot */
/** @var string $app */
$return = [

    'aliases' => require Root . '/configs/zmd/alias.php',

    'params' => [
        'adminEmail' => 'asror.zk@gmail.com',
        'icon-framework' => 'far',
        'bSubmitBtnState' => true,
        'bsVersion' => '4.x',
    ],

    'vendorPath' => Root . '/vendor',

    'basePath' => Root . $basePath,

    'modules' => [
        'treemanager' => [
            'class' => '\kartik\tree\Module',
        ],
        'dynagrid' => [
            'class' => kartik\dynagrid\Module::class,
            'defaultPageSize' => 20,
            'dbSettings' => [
                'tableName' => 'dyna_dynagrid'
            ],
            'dbSettingsDtl' => [
                'tableName' => 'dyna_dynagrid_dtl'
            ],
            'minPageSize' => $boot->env('minPageSize'),
            'maxPageSize' => $boot->env('maxPageSize'),

        ],
    ],

    'bootstrap' => [
        // 'log',
    ],

    'controller' => null,

    'controllerNamespace' => null,

    'charset' => 'UTF-8',

    'components' => [


        //  yii\web\View
        'view' => [
            'class' => ZView::class,
        ],


        'authManager' => [
            'class' => DbManager::class,
            'defaultRoles' => [],

            'itemTable' => 'auth_item',
            'itemChildTable' => 'auth_item_child',
            'assignmentTable' => 'auth_assignment',
            'ruleTable' => 'auth_rule',

            'cache' => $boot->env('cache')
        ],


        'urlManager' => require Root . '/configs/zmd/zurls.php',

        'log' => null,

        /**
         *
         * System components
         */

        'formatter' => [
            'class' => ZFormatter::class,
            'nullDisplay' => '',
        ],

        'telegram' => [
            'class' => ZTelegram::class,
            'token' => $boot->env('tgBotID'),
        ],
        'sphinx' => [
            'class' => 'yii\sphinx\Connection',
            'dsn' => 'postgres:host=10.10.3.207;port=5432;',
            'username' => 'postgres',
            'password' => 'serverpass1234',
        ],
        
        'geoip' => ['class' => 'lysenkobv\GeoIP\GeoIP'],

        'mailer' => [
            'class' => Mailer::class,
            'viewPath' => '@zetsoft/webhtm/' . App . '/mailer',
            'htmlLayout' => '@zetsoft/layouts/eyuf/mail/html',
            'textLayout' => '@zetsoft/layouts/eyuf/mail/text',
            'transport' => [
                'class' => Swift_SmtpTransport::class,
                'host' => $boot->env('mailHost'),
                'username' => $boot->env('mailUsername'),
                'password' => $boot->env('mailPassword'),
                'port' => $boot->env('mailPort'),
                'encryption' => $boot->env('mailEncryption'),
            ],
            'messageConfig' => [
                'charset' => 'UTF-8',
                'from' => [$boot->env('mailUsername') => $boot->env('mailTitle')]
            ],


            /*'useFileTransport' => true,
            'enableSwiftMailerLogging' => true,*/
        ],

    ],

    'extensions' => null,

    'id' => App,

    'name' => $boot->env('appTitle'),

    'runtimePath' => $boot->folderRuntime,

    'language' => $boot->env('language'),

    'sourceLanguage' => $boot->env('language'),

    'version' => $boot->env('appVersion'),

];


return $return;