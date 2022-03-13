<?php

namespace Uniform\Guards;

use Kirby\Cms\App;
use Kirby\Toolkit\I18n;


/**
 * Class SimpleCaptchaGuard
 *
 * Checks whether inlined captcha was solved correctly
 */
class SimpleCaptchaGuard extends Guard
{
    /**
     * Session key for the inlined captcha
     *
     * @var string
     */
    const FLASH_KEY = 'simple-captcha';


    /**
     * Captcha field name
     *
     * @var string
     */
    const FIELD_NAME = 'simple-captcha';


    /**
     * 'Niceize' string
     *
     * See https://github.com/Gregwar/Captcha/blob/master/src/Gregwar/Captcha/PhraseBuilder.php#L65
     *
     * @param string $string Input string
     * @return string Formatted string
     */
    public static function niceize(string $string): string
    {
        return strtr(strtolower($string), '01', 'ol');
    }


    /**
     * Checks whether field for inlined captcha was solved correctly
     *
     * @return void
     */
    public function perform()
    {
        # Determine user input
        $field = $this->option('field', self::FIELD_NAME);
        $input = App::instance()->request()->body()->get($field);

        # If empty ..
        if (empty($input)) {
            # .. fail early
            $this->reject(I18n::translate('local-captcha-empty'), self::FIELD_NAME);
        }

        # Retrieve expected result
        $result = App::instance()->session()->get(self::FLASH_KEY, null);

        # If no match found ..
        if ($result === null || static::niceize($input) != static::niceize($result)) {
            # .. fail ultimately
            $this->reject(I18n::translate('local-captcha-invalid'), $field);
        }

        # .. otherwise, remove field from form data
        $this->form->forget($field);
    }
}
