<?php


use zetsoft\system\targets\ZTelegram;
use yii\httpclient\StreamTransport;
use zetsoft\system\actives\ZActiveQuery;
use zetsoft\system\actives\ZActiveRecord;
use zetsoft\system\targets\ZConsoleTarget;
use zetsoft\system\targets\ZFileTarget;
use zetsoft\system\targets\ZTelegramTarget;
use yii\helpers\ArrayHelper;


const Cat_InsertField = 'InsertField';
const Cat_ModelSave = 'ModelSave';
const Cat_AudioInfo = 'AudioInfo';
const Cat_MethodStart = 'MethodStart';
const Cat_ArrayCount = 'ArrayCount';
const Cat_SQLWaiting = 'SQLWaiting';
const Cat_SQLQuery = 'SQLQuery';
const Cat_GenModel = 'GenModel';
const Cat_GenModelTwo = 'GenModelTwo';


$isLogExcept = $boot->env('logExcept');

$logExcepts = [
    'yii\db\Connection::open',
    'yii\db\Command::query',
    'yii\db\Command::execute',
    'yii\db\Transaction::begin',
    'yii\db\Transaction::commit',
    'yii\httpclient\StreamTransport::send',

    ZTelegram::class,
    ZActiveRecord::class,
    ZActiveQuery::class,
];


/**
 *
 * Error Categories
 */

const Cat_GetContent = 'GetContent';
const Cat_CannotPlay = 'CannotPlay';
const Cat_Exception = 'Exception';

$logErrorExcepts = [
	Cat_GetContent,
    Cat_CannotPlay,
    Cat_Exception,
];


$logTelegram = false;
$logFile = false;

/**
 *
 * perms
 */

const Cmd = 'Cmd_';
const Web = 'Web_';

$logAllowTgAll = [
    Cmd . 'Error' => true,
    Cmd . 'Warns' => true,
    Cmd . 'Infos' => false,
    Cmd . 'Trace' => false,
    Cmd . 'Profi' => false,

    Web . 'Error' => true,
    Web . 'Warns' => true,
    Web . 'Infos' => false,
    Web . 'Trace' => false,
    Web . 'Profi' => false,
];


$cmdLogAllows = [
    'Error' => true,
    'Warns' => true,
    'Infos' => true,
    'Trace' => false,
    'Profi' => false,
];

$logAllowFileALL = [
    Cmd . 'Error' => true,
    Cmd . 'Warns' => true,
    Cmd . 'Infos' => false,
    Cmd . 'Trace' => false,
    Cmd . 'Profi' => false,

    Web . 'Error' => true,
    Web . 'Warns' => true,
    Web . 'Infos' => false,
    Web . 'Trace' => false,
    Web . 'Profi' => false,
];


/**
 *
 *
 * Except Categories
 *
 */

if ($boot->isCLI())
    $logVars = ['!_GET', '!_POST', '!_FILES', '!_COOKIE', '!_SESSION', '_SERVER.COMPUTERNAME', '_SERVER.argv'];
else
    $logVars = ['_GET', '_POST', '_FILES', '!_COOKIE', '!_SESSION', '_SERVER.COMPUTERNAME'];

/**
 *
 * Log Flushing
 * FlushInterval
 */

switch (true) {
    case $boot->userDev():
    case $boot->isCLI():
        $flushInterval = 1;
        $exportInterval = 1;
        break;

    default:
        $flushInterval = 300;
        $exportInterval = 300;
}


/**
 *
 *
 * Core Items
 */

$coreALL = [
    'exportInterval' => $exportInterval,
    'logVars' => $logVars,
    'prefix' => function ($message) {
        return null;
    },
    'categories' => [],

    /**
     *
     * Read Only or Available since version 2.0.13
     */

    //  $behaviors public read-only property
    //  'messages' => [],
    //  'enabled' => true,
    //  'microtime' => false,
];


$coreConsole = [
    'class' => ZConsoleTarget::class,

    'enableContextMassage' => false,
    'isDisplayCategory' => false,
    'isDisplayDate' => false,
    'padSize' => 15,

];


$coreFile = [
    'class' => ZFileTarget::class,

    'enableRotation' => true,
    'maxFileSize' => 1024 * 5,
    'maxLogFiles' => 3,
    'rotateByCopy' => true,

    'dirMode' => 0775,
    'fileMode' => null,
];


$coreTg = [
    'class' => ZTelegramTarget::class,
];


$levels = [
    'Error',
    'Warns',
    'Infos',
    'Trace',
    'Profi',
];


$names = [
    'Error' => 'error',
    'Warns' => 'warning',
    'Infos' => 'info',
    'Trace' => 'trace',
    'Profi' => 'profile',
];

if ($boot->isCLI())
    $prefix = Cmd;
else
    $prefix = Web;


$tgChatIDAll = [
    Cmd . 'Error' => '-188642948',
    Cmd . 'Warns' => '-289847277',
    Cmd . 'Infos' => '-198666368',
    Cmd . 'Trace' => '-292478865',
    Cmd . 'Profi' => '-302250161',

    Web . 'Error' => '-365751846',
    Web . 'Warns' => '-140838763',
    Web . 'Infos' => '-311458786',
    Web . 'Trace' => '-268240169',
    Web . 'Profi' => '-258440412',
];

$tgChatIDs = [];

foreach ($levels as $level)
    $tgChatIDs[$level] = $tgChatIDAll[$prefix . $level];


/**
 *
 * Telegram Target
 */


$logAllowTg = [];

foreach ($levels as $level)
    $logAllowTg[$level] = $logAllowTgAll[$prefix . $level];


/**
 *
 *
 * Allow Telegram
 */

if (!$logTelegram)
    foreach ($logAllowTg as $key => $value) {
        $logAllowTg[$key] = false;
    }


/**
 *
 *
 * File Target
 */


$logAllowFile = [];
foreach ($levels as $level)
    $logAllowFile[$level] = $logAllowFileALL[$prefix . $level];


/**
 *
 * Begin Processing
 */


$logTargets = [];


/**
 *
 * Telegram Target
 */

foreach ($levels as $key => $sLevel) {

    $excepts = [];

    if ($key <= 1 && $isLogExcept)
        $excepts = $logErrorExcepts;

    if ($key >= 2 && $isLogExcept)
        $excepts = $logExcepts;


    if ($logTelegram)
        if ($logAllowTg[$sLevel])
            $logTargets[] = ArrayHelper::merge(
                $coreALL,
                $coreTg,
                [
                    'chatId' => $tgChatIDs[$level],
                    'levels' => [$names[$level]],
                    'except' => $excepts,
                ]
            );

    if ($logFile)
        if ($logAllowFile[$sLevel])
            $logTargets[] = yii\helpers\ArrayHelper::merge(
                $coreALL,
                $coreFile,
                [
                    'levels' => [$names[$sLevel]],
                    'logFile' => "{$boot->folderLoggers}/{$prefix}{$sLevel}.log",
                    'except' => $excepts,
                ]
            );


    if ($boot->isCLI())
        if ($cmdLogAllows[$sLevel])
            $logTargets[] = yii\helpers\ArrayHelper::merge(
                $coreALL,
                $coreConsole,
                [
                    'levels' => [$names[$sLevel]],
                    'except' => $excepts,
                ]
            );


}


$log = [
    'flushInterval' => $flushInterval,
    'traceLevel' => 10,
    'target' => $logTargets,
];

return $log;

