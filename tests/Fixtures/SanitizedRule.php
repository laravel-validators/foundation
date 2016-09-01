<?php

namespace Tests\Fixtures;

class SanitizedRule
{
    public function validate($attribute, $value, $parameters)
    {
        return $value === 'bar';
    }

    public function sanitize($value)
    {
        return str_replace('foo', 'bar', $value);
    }
}
