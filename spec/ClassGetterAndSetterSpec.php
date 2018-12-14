<?php

declare(strict_types=1);

namespace Marcosh\OphpticsSpec;

use Marcosh\Ophptics\ClassGetterAndSetter;

class Foo
{
    private $bar;

    public function __construct($bar)
    {
        $this->bar = $bar;
    }

    public function bar()
    {
        return $this->bar;
    }

    public function withBar($bar)
    {
        $ret = clone $this;

        $ret->bar = $bar;

        return $ret;
    }
}

describe('ClassGetterAndSetter', function () {
    $barLens = new ClassGetterAndSetter('bar', 'withBar');

    $foo = new Foo(42);

    it('gets the correct value', function () use ($barLens, $foo) {
        expect($barLens->get($foo))->toBe(42);
    });

    it('sets the value correctly', function () use ($barLens, $foo) {
        expect($barLens->set($foo, 37))->toEqual(new Foo(37));
    });

    it('does not modify the original data structure', function () use ($barLens, $foo) {
        $oldFoo = clone $foo;

        $newFoo = $barLens->set($foo, 'Downing Street');

        expect($foo)->toEqual($oldFoo);
        expect($newFoo)->not->toEqual($foo);
    });
});
