<?php

use Uniform\Guards\SimpleCaptchaGuard;

use Kirby\Cms\App;
use Kirby\Toolkit\A;
use Kirby\Toolkit\Html;

use SimpleCaptcha\Builder;


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
        $length = option('simple-captcha.length', 5);
        $charset = option('simple-captcha.charset', 'abcdefghijklmnpqrstuvwxyz123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');

        # (2) Initialize phrase builder
        $phrase = Builder::buildPhrase($length, $charset);

        # Generate captcha
        # (1) Initialize captcha builder
        $builder = Builder::create($phrase);

        # (2) Configure it
        # (a) Font files
        if ($fonts = option('simple-captcha.fonts', null)) {
            $builder->fonts = $fonts;
        }

        # (b) Background distortion
        $builder->distort = option('simple-captcha.distort', true);

        # (c) Font interpolation
        $builder->interpolate = option('simple-captcha.interpolate', true);

        # (d) Maximum lines behind & in front captcha text
        $maxLinesBehind = option('simple-captcha.maxLinesBehind', null);

        if (is_int($maxLinesBehind)) {
            $builder->maxLinesBehind = $maxLinesBehind;
        }

        $maxLinesFront = option('simple-captcha.maxLinesFront', null);

        if (is_int($maxLinesFront)) {
            $builder->maxLinesFront = $maxLinesFront;
        }

        # (e) Angle & offset of captcha text
        $builder->maxAngle = option('simple-captcha.maxAngle', 8);
        $builder->maxOffset = option('simple-captcha.maxOffset', 5);

        # (f) Various colors
        if ($bgColor = option('simple-captcha.bgColor', null)) {
            $builder->bgColor = $bgColor;
        }

        if ($lineColor = option('simple-captcha.lineColor', null)) {
            $builder->lineColor = $lineColor;
        }

        if ($textColor = option('simple-captcha.textColor', null)) {
            $builder->textColor = $textColor;
        }

        # (g) Background image
        if ($bgImage = option('simple-captcha.bgImage', null)) {
            # If file object was passed ..
            if (is_a($bgImage, 'Kirby\Cms\File')) {
                # .. use its filepath
                $bgImage = $bgImage->root();
            }

            $builder->bgImage = $bgImage;
        }

        # (h) Effects
        $builder->applyEffects = option('simple-captcha.applyEffects', true);
        $builder->applyNoise = option('simple-captcha.applyNoise', true);
        $builder->noiseFactor = option('simple-captcha.noiseFactor', 2);
        $builder->applyPostEffects = option('simple-captcha.applyPostEffects', true);
        $builder->applyScatterEffect = option('simple-captcha.applyScatterEffect', true);

        # (i) Whether each captcha character should be picked at random
        $builder->randomizeFonts = option('simple-captcha.randomizeFonts', true);

        # (3) Build captcha image
        # (a) Determine image width & height
        $width = option('simple-captcha.width', 150);
        $height = option('simple-captcha.height', 40);

        # (b) Party time
        $builder->build($width, $height);

        # Store answer
        App::instance()->session()->set(SimpleCaptchaGuard::FLASH_KEY, $builder->phrase);

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
