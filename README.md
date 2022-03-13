# Simple captcha

This plugin implements a simple captcha guard for Martin Zurowietz' [`kirby-uniform`](https://github.com/mzur/kirby-uniform) plugin for Kirby v3 - dependency-free & GDPR-friendly, powered by [`Gregwar/Captcha`](https://github.com/Gregwar/Captcha).

**Note:** The generated image uses a data URI as its `src` attribute, everything else is handled by Kirby's [session object](https://getkirby.com/docs/reference/objects/cms/app/session).


## Getting started

Use one of the following methods to install & use `refbw/uniform-simple-captcha`:


### Git submodule

If you know your way around Git, you can download this plugin as a [submodule](https://github.com/blog/2104-working-with-submodules):

```text
git submodule add https://codeberg.org/refbw/uniform-simple-captcha.git site/plugins/uniform-simple-captcha
```


### Composer

```text
composer require s1syphos/refbw/uniform-simple-captcha
```


### Clone or download

1. [Clone](https://codeberg.org/refbw/uniform-simple-captcha.git) or [download](https://codeberg.org/refbw/uniform-simple-captcha/archive/main.zip) this repository.
2. Unzip / Move the folder to `site/plugins`.


## Usage

### Template

There are two helper functions:

- `simpleCaptcha()` for the captcha `img` tag
- `simpleCaptchaField()` for its `input` field

You may use them in your template like so:

```php
# Captcha image
# Vanilla
<?= simpleCaptcha() ?>

# Custom attributes
<?= simpleCaptcha(['class' => 'some-class', 'title' => 'solve me!' ]) ?>

# Input field
<?= simpleCaptchaField() ?>

# Custom `id` (eg for use with a label)
<label for="my-captcha">Not so fast!</label>
<?= simpleCaptchaField('my-captcha', ['class' => 'some-class']) ?>
```


### Controller

After that, you have to enable the guard by calling `simplecaptchaGuard()` on the `$form` object.

For more information, check out the `kirby-uniform` docs on its [magic methods](https://kirby-uniform.readthedocs.io/en/latest/guards/guards/#magic-methods):

```php
$form = new Form();

if ($kirby->request()->is('POST')) {
    # Call security
    $form->simplecaptchaGuard();

    # .. more code
}
```


### Configuration

You may change certain options from your `config.php` globally (`'simple-captcha.optionName'`):

| Option            | Type   | Default          | Description                    |
| ----------------- | ------ | ---------------- | ------------------------------ |
| `'length'`        | int    | `5`              | Number of characters           |
| `'charset'`       | string | a-z, A-Z, 0-9    | Allowed characters (as string) |
| `'interpolation'` | bool   | `true`           | Enables font interpolation     |
| `'distortion'`    | bool   | `true`           | Enables background distortion  |
| `'bg-colors'`     | array  | random           | RGB values, eg `[0, 255, 0]`   |


## License

This plugin is licensed under the [MIT License](LICENSE), but **using Kirby in production** requires you to [buy a license](https://getkirby.com/buy).
