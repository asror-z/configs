<?php

/**
 *
 *
 * Author:  Asror Zakirov
 * https://www.linkedin.com/in/asror-zakirov
 * https://www.facebook.com/asror.zakirov
 * https://github.com/asror-z
 *
 */

use zetsoft\system\module\ZCmdApp;
use zetsoft\system\module\ZWebApp;


const CLI = PHP_SAPI === 'cli';
const EOL = CLI ? PHP_EOL : '<br />';
const TAB = "\t";
const Nbsp = '&nbsp;';
const Azl = null;


define('Root', dirname(__DIR__));
define('TApp', stripos(Root, 'T:') !== false);

$constant = 'constant';


/**
 *
 * Boot Load
 */
require Root . '/configs/ALL/Boot.php';
$boot = new Boot();
$boot->init();

$boot->start();
$boot->returnTimer = false;


#region vendors

/**
 *
 * vendors
 */

require Root . '/vendors/kernel/yiisofts/vendor/autoload.php';
require Root . '/vendors/utility/ALL/vendor/autoload.php';

require Root . '/vendors/utility/collect/vendor/autoload.php';
require Root . '/vendors/netter/geoip/vendor/autoload.php';
require Root . '/vendors/kernel/laravel/vendor/autoload.php';

require Root . '/vendors/string/ALL/vendor/autoload.php';
require Root . '/vendors/kernel/symfon/vendor/autoload.php';


/*
require Root . '/vendors/netter/payer/vendor/autoload.php';
require Root . '/vendors/netter/tgbot/vendor/autoload.php';
require Root . '/vendors/parser/html/vendor/autoload.php';
require Root . '/vendors/netter/ALL/vendor/autoload.php';
require Root . '/vendors/fileapp/office/vendor/autoload.php';
require Root . '/vendors/netter/phone/vendor/autoload.php';

require Root . '/vendors/debug/ALL/vendor/autoload.php';
require Root . '/vendors/image/ALL/vendor/autoload.php';
require Root . '/vendors/fileapp/ALL/vendor/autoload.php';
require Root . '/vendors/utility/league/vendor/autoload.php';
require Root . '/vendors/thread/reacts/vendor/autoload.php';
require Root . '/vendors/utility/spatie/vendor/autoload.php';
require Root . '/vendors/debug/tester/vendor/autoload.php';
require Root . '/vendors/netter/acme/vendor/autoload.php';
require Root . '/vendors/thread/amphp/vendor/autoload.php';*/


#endregion

$boot->finish();


/**
 *
 * Core Requires
 */
require Root . '/service/ALL/ALL.php';

require Root . '/system/actives/ZConnection.php';
require Root . '/system/except/ZErrorHandler.php';
require Root . '/system/helpers/ZFileHelper.php';
require Root . '/system/helpers/ZStringHelper.php';
require Root . '/system/behave/ZUrlManager.php';

/** @var \Boot $boot */
$boot->gone();

require Root . '/binary/speek/ZALL.php';
require Root . '/system/control/ZCoreTrait.php';
require Root . '/system/module/ZCmdApp.php';
require Root . '/system/module/ZWebApp.php';

/**
 *
 * Configs
 */


require Root . '/configs/ALL/start.php';
$boot->apps();
require Root . '/configs/data/ALL.php';





if (Mode === 'init')
    return true;


/**
 *
 * Yii Mode
 */
require Root . '/vendors/kernel/yiisofts/vendor/yiisoft/yii2/Yii.php';
Yii::$classMap = array_merge(Yii::$classMap, $boot->map());


if ($boot->isCLI()) {
    defined('STDIN') or define('STDIN', fopen('php://stdin', 'rb'));
    defined('STDOUT') or define('STDOUT', fopen('php://stdout', 'wb'));
}


/**
 *
 * Configuration
 */

$main = require Root . '/configs/ALL/ALL.php';
$cache = require Root . '/configs/ALL/cache.php';
$data = require Root . '/configs/data/' . App . '.php';
$all = require Root . '/configs/' . Mode . '/ALL.php';
$app = require Root . '/configs/' . Mode . '/' . App . '.php';


$config = yii\helpers\ArrayHelper::merge($main, $cache, $data, $all, $app, ZALL::mine());

$boot->finish();

/**
 *
 * Execution
 */

if ($boot->isCLI()) {
    $application = new ZCmdApp($config);
} else {
    $application = new ZWebApp($config);
}


$exitCode = $application->run();
exit($exitCode);
