<?php

use Kirby\Cms\App;
use Kirby\Toolkit\I18n;
use Uniform\Guards\SimpleCaptchaGuard;

use Gregwar\Captcha\CaptchaBuilder;


if (!function_exists('simpleCaptcha')) {
    /**
     * Creates `img` element while generating the captcha
     *
     * @return string HTML `img` element
     */
    function simpleCaptcha(): string
    {
        # Generate captcha
        $builder = new CaptchaBuilder;
        $builder->build();

        # Store answer
        App::instance()->session()->set(SimpleCaptchaGuard::FLASH_KEY, $builder->getPhrase());

        # Create `img` element from captcha as data URI
        return sprintf('<img src="%s">', $builder->inline());
    }
}


if (!function_exists('simpleCaptchaField')) {
    /**
     * Creates `input` element for solving the generated captcha
     *
     * @param string $name Form field `name`
     * @param string $class Form field `class`
     * @return string HTML `input` element
     */
    function simpleCaptchaField(?string $name = null, ?string $class = null): string
    {
        $name = $name ?? SimpleCaptchaGuard::FIELD_NAME;
        $class = $class ?? 'local-captcha';

        return sprintf('<input type="text" name="%s" class="%s">', $name, $class);
    }
}
