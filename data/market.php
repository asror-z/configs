<?php

use zetsoft\system\actives\ZConnection;
use zetsoft\system\helpers\ZArrayHelper;
use yii\db\Connection;
use yii\helpers\ArrayHelper;
use zetsoft\system\schema\PgSqlSchema;

/**
 *
 * Author:  Asror Zakirov
 * Created: 26.06.2017 14:27
 * https://www.linkedin.com/in/asror-zakirov-167961a9
 * https://www.facebook.com/asror.zakirov
 * https://github.com/asror-z
 */


if ($boot->isWindows()) {

/*    if ($boot->namePC === 'PC210')
        $host = Boot::ipLocal;
    else*/
        $host = $coreIP;
} else
    $host = 'postgres';

global $boot;
$aDb = [
    'components' => [

        'db' => [
            'class' => ZConnection::class,
            'dbType' => ZConnection::Db_PgSQL,
            'dbHost' => $host,
            'dbName' => $boot->env('dbName'),
            'username' => $boot->env('dbUsername'),
        'password' => $boot->env('dbPassword'),
        ],


        'sphinx' => [
            'class' => 'yii\sphinx\Connection',
            'dsn' => 'pgsql:host=10.10.3.61;port=5432;',
            'username' => 'postgres',
            'password' => 'serverpass1234',
        ],

        'db2' => [
            'class' => ZConnection::class,
            'dbType' => ZConnection::Db_MySQL,
            'dbHost' => '10.10.3.60',
            'dbName' => 'asteriskcdrdb',
            'username' => 'root',
            'password' => '',

        ],

        /*'db3' => [
            'class' => ZConnection::class,
            'dbType' => ZConnection::Db_MySQL,
            'dbHost' => '10.10.3.60',
            'dbName' => 'asterisk',
            'username' => 'root',
            'password' => '',
        ],*/

    ]
];

return $aDb;
