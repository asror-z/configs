<?php


/**
 *
 *
 * Author:  Asror Zakirov
 *
 * https://www.linkedin.com/in/asror-zakirov
 * https://www.facebook.com/asror.zakirov
 * https://github.com/asror-z
 *
 */


use Illuminate\Contracts\Routing\ResponseFactory;
use zetsoft\system\Az;
use zetsoft\system\helpers\ZCollect;
use zetsoft\system\helpers\ZStringHelper;
use zetsoft\system\kernels\ZView;

/**
 *
 * Check Root
 */

ini_set('memory_limit', -1);

/**
 *
 * Main Env
 */
$envPath = Root . '/configs/env';
Dotenv\Dotenv::createMutable($envPath, 'ALL.env')->load();
Dotenv\Dotenv::createMutable($envPath, 'App.env')->load();


/**
 *
 * User Profile
 */
$envPathUser = Root . '/configs/user';

$namePC = strtolower($boot->namePC);
$nameUser = strtolower($boot->nameUser);

Dotenv\Dotenv::createMutable($envPathUser, $namePC . '.env')->safeLoad();
Dotenv\Dotenv::createMutable($envPathUser, $namePC . '-' . $nameUser . '.env')->safeLoad();


/**
 *
 * APP Profile from Cmd or Env
 */
$envApp = getenv('envApp');

if ($boot->isCLI()) {
    $cmdline = null;
    $cmdlineAll = null;
    for ($i = 2; $i < 4; $i++)
        if (array_key_exists($i, $argv)) {

            switch (true) {
                case ZStringHelper::find($argv[$i], '--app='):
                    $cmdline = $argv[$i];
                    continue 2;
                    break;

                case ZStringHelper::find($argv[$i], '--class='):
                    $cmdlineAll .= '"' . $argv[$i] . '" ';
                    break;

                default:
                    $cmdlineAll .= $argv[$i] . ' ';
                    break;
            }


        }


    /**
     *
     * Get app name
     */
    $cmdApp = null;
    if ($cmdline !== null)
        $cmdApp = str_replace('--app=', '', $cmdline);
}

if (!defined('App'))
    if ($cmdApp !== null)
        define('App', $cmdApp);
    else
        define('App', $envApp);


/**
 *
 * CLI
 */

if (!CLI)
    $basePath = '/ex' . Mode . '/' . App;
else
    $basePath = '/ex' . Mode;

Dotenv\Dotenv::createMutable($envPath, App . '.env')->safeLoad();
Dotenv\Dotenv::createMutable($envPath, App . '-' . $boot->namePC . '.env')->safeLoad();


/**
 *
 * Yii Debug
 */
define('YII_DEBUG', true);
define('YII_ENV', 'prod');

ini_set('display_errors', 1);
error_reporting(E_ALL);


#region Core

function bname($tableName, $ext = null)
{

    global $boot;

    if (!$boot->isWindows())
        $tableName = str_replace('\\', '/', $tableName);

    $tableName = basename($tableName, $ext);

    return $tableName;
}

function vd(...$vars)
{
    global $boot;

    foreach ($vars as $var)
        echo $boot->logs($var);
}

function vdd(...$vars)
{
    global $boot;
    foreach ($vars as $var)
        echo $boot->logs($var);

    die(1);
}

#endregion


#region Trace

function vt(...$vars)
{
    foreach ($vars as $var)
        vd($var);

    echo trace();
}

function vtd(...$vars)
{
    foreach ($vars as $var)
        vt($var);

    die(1);
}

#endregion


#region File

function vf(...$vars)
{
    global $boot;
    $data = null;

    foreach ($vars as $v) {
        $data .= $boot->logs($v, true);
    }

    $file = Root . '/storing/context/' . $boot->fileAll() . '.htm';
    file_put_contents($file, $data);
}

function vfd(...$vars)
{
    vf($vars);
    die(1);
}


#endregion

#region Trace

function trace($backTrace = false)
{
    global $boot;
    return $boot->trace($backTrace);
}

/**
 *
 * Function  zcollect
 * @param $value
 * @return  ZCollect
 * https://laravel.com/docs/8.x/collections
 */
function zcollect($value)
{
    return new ZCollect($value);
}


#endregion




