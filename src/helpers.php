<?php

use Kirby\Cms\App;
use Kirby\Toolkit\A;
use Kirby\Toolkit\Html;
use Kirby\Toolkit\I18n;
use Uniform\Guards\SimpleCaptchaGuard;

use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;


if (!function_exists('simpleCaptcha')) {
    /**
     * Creates `img` element while generating the captcha
     *
     * @param array $attributes Custom captcha image HTML attributes
     * @return string HTML `img` element
     */
    function simpleCaptcha(array $attributes = []): string
    {
        # Build captcha phrase
        # (1) Fetch options regarding captcha generation
        $length = kirby()->option('simple-captcha.length', 5);
        $charset = kirby()->option('simple-captcha.charset', 'abcdefghijklmnpqrstuvwxyz123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');

        # (2) Initialize phrase builder
        $phrase = new PhraseBuilder($length, $charset);

        # Generate captcha
        # (1) Initialize captcha builder
        $builder = new CaptchaBuilder(null, $phrase);

        # (2) Configure it
        # (a) Font interpolation
        $builder->setInterpolation(kirby()->option('simple-captcha.interpolation', true));

        # (b) Background distortion
        $builder->setDistortion(kirby()->option('simple-captcha.distortion', true));

        # (c) Background colors
        if ($colors = kirby()->option('simple-captcha.bg-colors', null)) {
            $builder->setBackgroundColor($colors[0], $colors[1], $colors[2]);
        }

        # (3) Build captcha image
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
