<?php

namespace Uniform\Guards;

use Kirby\Cms\App;

use SimpleCaptcha\Builder;


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
            $this->reject(t('local-captcha-empty'), self::FIELD_NAME);
        }

        # Retrieve expected result
        $result = App::instance()->session()->get(self::FLASH_KEY, null);

        # If no match found ..
        if ($result === null || !Builder::create()->compare($input, $result)) {
            # .. fail ultimately
            $this->reject(t('local-captcha-invalid'), $field);
        }

        # .. otherwise, remove field from form data
        $this->form->forget($field);
    }
}
