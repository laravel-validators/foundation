<?php

namespace Tests;

use Illuminate\Support\Facades\Validator;

class SanitizedValidatorsTest extends TestCase
{
    public function test_sanitizer_works()
    {
        $v = Validator::make(['foo' => 'foo'], ['foo' => 'sanitized_rule']);
        $this->assertTrue($v->passes());
    }
}
