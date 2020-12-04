<?php

/**
 *
 *
 * Author:  Asror Zakirov
 * Date:    10.06.2019
 * https://www.linkedin.com/in/asror-zakirov
 * https://www.facebook.com/asror.zakirov
 * https://github.com/asror-z
 *
 */


use codemix\localeurls\UrlManager;
use yii\helpers\ArrayHelper;
use yii\rest\UrlRule;
use yii\web\UrlNormalizer;

use zetsoft\service\cores\Langs;
use zetsoft\system\behave\ZUrlManager;


$urlsManConsole = [
    'class' => \yii\web\UrlManager::class,
    'scriptUrl' => $boot->env('consoleURL'),
    'baseUrl' => $boot->env('consoleURL')
];

$urlsMan = [
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'normalizer' => [
        'class' => UrlNormalizer::class,
        'action' => UrlNormalizer::ACTION_REDIRECT_TEMPORARY,
    ],
    'enableStrictParsing' => false,

    'ruleConfig' => [
        'class' => yii\web\UrlRule::class
    ],

    'routeParam' => 'go',
    'suffix' => '.aspx',
    'cache' => 'cache',
];


$urlsManCore = ArrayHelper::merge($urlsMan, [
    'class' => yii\web\UrlManager::class,
]);



$urlsManMix = ArrayHelper::merge($urlsMan, [

    'class' => ZUrlManager::class,
    'languages' => $boot->env('activelang'),
    'enableLocaleUrls' => true,
    'keepUppercaseLanguageCode' => true,
    'enableDefaultLanguageUrlCode' => true,
    'enableLanguagePersistence' => false,
    'enableLanguageDetection' => true,
    'languageCookieName' => '_language',
    'languageCookieDuration' => 10 * 60 * 60,
    'languageSessionKey' => '_language',
//      'on languageChanged' => '\app\components\User::onLanguageChanged',

    'geoIpLanguageCountries' => [],

    'ignoreLanguageUrlPatterns' => [
        // route pattern => url pattern
        '#^site/(login|register)#' => '#^(signin|signup)#',
        '#^api/#' => '#^api/#',
    ],

]);

/** @var Boot $boot */
if ($boot->isCLI())
    return $urlsManConsole;
else
    return $urlsManMix;
