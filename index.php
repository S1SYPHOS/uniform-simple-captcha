<?php

/**
 * 'SimpleCaptcha' - Simple captcha guard for 'mzur/kirby-uniform' & Kirby v3
 *
 * @package   Kirby CMS
 * @author    Martin Folkers <webmaster@refbw.de>
 * @link      http://codeberg.org/refbw/uniform-simple-captcha
 * @version   1.0.0
 * @license   MIT
 */

@include_once __DIR__ . '/vendor/autoload.php';
@include_once __DIR__ . '/src/helpers.php';

load([
    'Uniform\\Guards\\SimpleCaptchaGuard' => 'src/Guards/SimpleCaptcha.php'
], __DIR__);


use Kirby\Cms\App as Kirby;


Kirby::plugin('refbw/uniform-simple-captcha', [
	'translations' => [
        'de' => @include_once __DIR__ . '/i18n/de.php',
		'en' => @include_once __DIR__ . '/i18n/en.php',
		'nl' => @include_once __DIR__ . '/i18n/nl.php',
    ],
]);
