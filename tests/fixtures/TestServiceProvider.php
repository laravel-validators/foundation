<?php

use LaravelValidators\Foundation\ValidationServiceProvider;

class ServiceProvider extends ValidationServiceProvider
{
    protected $rules = [
        'class_rule' => Rule::class,
    ];

    public function rules()
    {
        $this->rule('closure_rule', function ($attribute, $value, $parameters, $validator) {
            return $value === 'bar';
        });
    }
}
