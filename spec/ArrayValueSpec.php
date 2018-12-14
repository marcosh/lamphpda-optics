<?php

declare(strict_types=1);

namespace Marcosh\OphpticsSpec;

use Marcosh\Ophptics\ArrayValue;

describe('ArrayValue', function () {
    $barLens = new ArrayValue('bar');

    $foo = [
        'bar' => 42,
        'baz' => 37
    ];

    it('gets the correct value', function () use ($barLens, $foo) {
        expect($barLens->get($foo))->toBe(42);
    });

    it('sets the value correctly', function () use ($barLens, $foo) {
        expect($barLens->set($foo, 23))->toEqual(['bar' => 23, 'baz' => 37]);
    });

    it('does not modify the original data structure', function () use ($barLens, $foo) {
        $oldFoo = $foo;

        $newFoo = $barLens->set($foo, 23);

        expect($foo)->toEqual($oldFoo);
        expect($newFoo)->not->toEqual($foo);
    });
});
