<?php

namespace Tests;

use Illuminate\Support\Facades\Validator;

class CustomValidatorsTest extends TestCase
{
    public function test_can_register_custom_validator()
    {
        $v = Validator::make(['foo' => 'bar'], ['foo' => 'closure_rule']);
        $this->assertTrue($v->passes());

        $v = Validator::make(['foo' => 'baz'], ['foo' => 'closure_rule']);
        $this->assertFalse($v->passes());

        $v = Validator::make(['foo' => 'bar'], ['foo' => 'class_rule']);
        $this->assertTrue($v->passes());

        $v = Validator::make(['foo' => 'baz'], ['foo' => 'class_rule']);
        $this->assertFalse($v->passes());
    }

    public function test_custom_validator_can_specify_custom_message()
    {
        $v = Validator::make(['foo' => 'baz'], ['foo' => 'class_rule']);
        $this->assertEquals('qux', $v->messages()->first('foo'));
    }
}
