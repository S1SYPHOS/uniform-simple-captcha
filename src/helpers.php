<?php

use Kirby\Cms\App;
use Kirby\Toolkit\A;
use Kirby\Toolkit\Html;
use Kirby\Toolkit\I18n;
use Uniform\Guards\SimpleCaptchaGuard;

use Gregwar\Captcha\CaptchaBuilder;


if (!function_exists('simpleCaptcha')) {
    /**
     * Creates `img` element while generating the captcha
     *
     * @param array $attributes Custom captcha image HTML attributes
     * @return string HTML `img` element
     */
    function simpleCaptcha(array $attributes = []): string
    {
        # Generate captcha
        $builder = new CaptchaBuilder;
        $builder->build();

        # Store answer
        App::instance()->session()->set(SimpleCaptchaGuard::FLASH_KEY, $builder->getPhrase());

        # Create `img` element from captcha as data URI
        return Html::img($builder->inline(), A::update([
            'class' => 'simple-captcha',
        ], $attributes));
    }
}


if (!function_exists('simpleCaptchaField')) {
    /**
     * Creates `input` element for solving the generated captcha
     *
     * @param string $id Form field `id`
     * @param array $attributes Custom form field HTML attributes
     * @return string HTML `input` element
     */
    function simpleCaptchaField(?string $id = null, array $attributes = []): string
    {
        return Html::tag('input', '', A::update([
            'id' => $id,
            'name' => SimpleCaptchaGuard::FIELD_NAME,
            'class' => 'simple-captcha-field',
        ], $attributes));
    }
}
