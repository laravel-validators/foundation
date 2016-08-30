<?php

namespace Tests\Fixtures;

use LaravelValidators\Foundation\ValidationServiceProvider;

class TestServiceProvider extends ValidationServiceProvider
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
