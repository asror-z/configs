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


#region vendori

/**
 *
 * vendori
 */

require Root . '/vendori/kernel/yiisofts/vendor/autoload.php';
require Root . '/vendori/utility/ALL/vendor/autoload.php';

require Root . '/vendori/utility/collect/vendor/autoload.php';
require Root . '/vendori/netter/geoip/vendor/autoload.php';
require Root . '/vendori/kernel/laravel/vendor/autoload.php';

require Root . '/vendori/string/ALL/vendor/autoload.php';
require Root . '/vendori/kernel/symfon/vendor/autoload.php';


/*
require Root . '/vendori/netter/payer/vendor/autoload.php';
require Root . '/vendori/netter/tgbot/vendor/autoload.php';
require Root . '/vendori/parser/html/vendor/autoload.php';
require Root . '/vendori/netter/ALL/vendor/autoload.php';
require Root . '/vendori/fileapp/office/vendor/autoload.php';
require Root . '/vendori/netter/phone/vendor/autoload.php';

require Root . '/vendori/debug/ALL/vendor/autoload.php';
require Root . '/vendori/image/ALL/vendor/autoload.php';
require Root . '/vendori/fileapp/ALL/vendor/autoload.php';
require Root . '/vendori/utility/league/vendor/autoload.php';
require Root . '/vendori/thread/reacts/vendor/autoload.php';
require Root . '/vendori/utility/spatie/vendor/autoload.php';
require Root . '/vendori/debug/tester/vendor/autoload.php';
require Root . '/vendori/netter/acme/vendor/autoload.php';
require Root . '/vendori/thread/amphp/vendor/autoload.php';*/


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
require Root . '/vendori/kernel/yiisofts/vendor/yiisoft/yii2/Yii.php';
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
