<?php

namespace Tests\Fixtures;

class Rule
{
    public function validate($attribute, $value, $parameters, $validator)
    {
        return $value === 'bar';
    }

    public static function message()
    {
        return 'qux';
    }
}
