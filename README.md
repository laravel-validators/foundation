# Foundation

[![Build Status](https://travis-ci.org/laravel-validators/foundation.svg)](https://travis-ci.org/laravel-validators/foundation)
[![Total Downloads](https://poser.pugx.org/laravel-validators/foundation/d/total.svg)](https://packagist.org/laravel-validators/foundation)
[![Latest Stable Version](https://poser.pugx.org/laravel-validators/foundation/v/stable.svg)](https://packagist.org/packages/laravel-validators/foundation)
[![Latest Unstable Version](https://poser.pugx.org/laravel-validators/foundation/v/unstable.svg)](https://packagist.org/packages/laravel-validators/foundation)
[![License](https://poser.pugx.org/laravel-validators/foundation/license.svg)](https://packagist.org/packages/laravel-validators/foundation)

An opinionated way to register custom validators in Laravel.

## Installation

Begin by installing the package through [Composer](https://getcomposer.org).

```bash
$ composer require laravel-validators/foundation
```

Create a service provider that extends `LaravelValidators\Foundation\ValidationServiceProvider`.

```php
<?php

namespace App\Providers;

use LaravelValidators\Foundation\ValidationServiceProvider as ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
  /**
   * The validation rules provided by your application.
   *
   * @var array
   */
  protected $rules = [
  //  'gender' => \App\Validators\GenderValidator::class,
  ];

  /**
   * Register the Closure based validators for the application.
   *
   * @return void
   */
  public function rules()
  {
    //
  }
}
```

Then add the following to the providers array in `config/app.php`.

```php
App\Providers\ValidationServiceProvider::class
```

## Usage

This package does not add any special functionality, it is essentially a wrapper for the following.

```php
Validator::extend('gender', App\Validators\GenderValidator::class, 'Must be male or female.');
```

### Creating validators

Like Laravel you can define custom validators as either closures or classes. Validators are typically stored in the `app/Validators` directory; however, you are free to choose your own storage location as long as your commands can be loaded by Composer.

### Generating validators

To create a new validator, use the `make:validator` Artisan command - provided by this package. This command will create a new validator class in the `app/Validators` directory. Don't worry if this directory does not exist in your application, since it will be created the first time you run the `make:validator` Artisan command.

```bash
$ php artisan make:validator GenderValidator
```

The `validate` method will be called when your validator is executed. You may place your validation logic in this method.

Let's take a look at an example validator. Note that we are able to inject any dependencies we need into the validator's constructor. The Laravel [service container](https://laravel.com/docs/5.3/container) will automatically inject all dependencies type-hinted in the constructor:

```php
<?php

namespace App\Validators;

class GenderValidator
{
  /**
   * Validate the given value.
   *
   * @param  string  $attribute
   * @param  mixed  $value
   * @param  array  $parameters
   * @param  \Illuminate\Contracts\Validation\Validator  $validator
   * @return  bool
   */
  public function validate($attribute, $value, $parameters, $validator)
  {
    return in_array($value, ['male', 'female']);
  }
}
```

Finally register the custom validator class in the `$rules` array in `App\Providers\ValidationServiceProvider`.

```php
protected $rules = [
  'gender' => \App\Validators\GenderValidator::class,
];
```

### Closure validators

Closure based validators provide an alternative to defining validators as classes. These are defined in the `App\Providers\ValidationServiceProvider` class using the inherited `->rule()` method.

```php
/**
 * Register the Closure based validators for the application
 *
 * @return void
 */
public function rules()
{
  $this->rule('gender', function ($attribute, $value, $parameters, $validator) {
    return in_array($value, ['male', 'female']);
  });
}
```

### Error messages

If your custom validator class provides a public static `message()` method, it will be used to retrieve the validation message.

```php
<?php

namespace App\Validators;

class GenderValidator
{
  /**
   * Validate the given value.
   *
   * @param  string  $attribute
   * @param  mixed  $value
   * @param  array  $parameters
   * @param  \Illuminate\Contracts\Validation\Validator  $validator
   * @return  bool
   */
  public function validate($attribute, $value, $parameters, $validator)
  {
    return in_array($value, ['male', 'female']);
  }
  
  public function sanitize($value)
  {
    return strtolower($value);
  }

  /**
   * Set the validation error message.
   *
   * @return string
   */
  public static function message()
  {
    return 'You can only specify male or female as your gender.';
  }
}
```

### Sanitize input

If you want to sanitise data before it is validated, you can specify a sanitize method on your validator.

```php
<?php

namespace App\Validators;

class GenderValidator
{
  /**
   * Validate the given value.
   *
   * @param  string  $attribute
   * @param  mixed  $value
   * @param  array  $parameters
   * @param  \Illuminate\Contracts\Validation\Validator  $validator
   * @return  bool
   */
  public function validate($attribute, $value, $parameters, $validator)
  {
    return in_array($value, ['male', 'female']);
  }
  
  /**
   * Sanitize the given value before it is validated. 
   *
   * @param mixed
   * @return mixed
   */
  public function sanitize($value)
  {
    return strtolower($value);
  }
}
```


## Credits

- [Christopher L Bray](https://github.com/brayniverse)
- [All contributors](../../contributors)
