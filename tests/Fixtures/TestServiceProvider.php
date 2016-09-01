<?php

use LaravelValidators\Foundation\ValidationServiceProvider;

class TestServiceProvider extends ValidationServiceProvider
{
    protected $rules = [
        'class_rule' => Rule::class,
        'sanitized_rule' => SanitizedRule::class,
    ];

    public function rules()
    {
        $this->rule('closure_rule', function ($attribute, $value, $parameters, $validator) {
            return $value === 'bar';
        });
    }
}
