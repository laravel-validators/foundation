<?php

namespace LaravelValidators\Foundation;

use Closure;
use Illuminate\Support\ServiceProvider;

abstract class ValidationServiceProvider extends ServiceProvider
{
    /**
     * The validation rules provided by your application.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Register the Closure based rules for the application.
     *
     * @return void
     */
    abstract protected function rules();

    /**
     * Register a Closure based rule with the application.
     *
     * @param  string  $name
     * @param  \Closure  $rule
     */
    public function rule($name, Closure $rule)
    {
        $this->rules[$name] = $rule;
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->rules();

        array_walk($this->rules, function ($validator, $rule) {
            $this->registerRule($rule, $validator);
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            \LaravelValidators\Foundation\Commands\ValidatorMakeCommand::class,
        ]);
    }

    /**
     * Register a validator with the application.
     *
     * @param  string  $rule
     * @param  mixed  $validator
     * @return void
     */
    protected function registerRule($rule, $validator)
    {
        if (method_exists($validator, 'sanitize')) {
            $validator = $this->wrapSanitizedValidator($validator);
        }

        $this->app->make('validator')->extend($rule, $validator, $this->getValidatorMessage($validator));
    }

    /**
     * Extract the error message from the validator.
     *
     * @param  string  $validator
     * @return string|null
     */
    private function getValidatorMessage($validator)
    {
        if (! method_exists($validator, 'message')) {
            return;
        }

        return call_user_func([$validator, 'message']);
    }

    /**
     * Wrap the validator in a closure which passes the
     * value through the sanitize method, which then
     * executes the validator with the new value.
     *
     * @param  $validator
     * @return \Closure
     */
    private function wrapSanitizedValidator($validator)
    {
        return function ($attribute, $value, $parameters, $factory) use ($validator) {
            $validator = $this->app->make($validator);

            return $validator->validate($attribute, $validator->sanitize($value), $parameters, $factory);
        };
    }
}
