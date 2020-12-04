<?php

/**
 *
 *
 * Author:  Asror Zakirov
 * Date:    02.07.2019
 * https://www.linkedin.com/in/asror-zakirov
 * https://www.facebook.com/asror.zakirov
 * https://github.com/asror-z
 *
 */

return [
    '@root' => Root,
    '@zetsoft' => Root,

    '@storing' => '@root/storing',
    '@runtime' => '@storing/runtime/' . Mode . '/' . App,

    '@vendor' => '@root/vendor',
    '@bower' => '@vendor/bower-asset',
    '@npm' => '@vendor/npm-asset',

    '@webroot' => '@root/exweb/' . App,
    '@web' => '@root/exweb/' . App,
];
