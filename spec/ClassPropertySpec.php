<?php

declare(strict_types=1);

namespace Marcosh\OphpticsSpec;

use Marcosh\Ophptics\ClassProperty;

class Bar
{
    public $foo;
}

describe('ClassProperty', function () {
    $fooLens = new ClassProperty('foo');

    $bar = new Bar();
    $bar->foo = 42;

    it('gets the correct value', function () use ($fooLens, $bar) {
        expect($fooLens->get($bar))->toBe(42);
    });

    it('sets the value correctly', function () use ($fooLens, $bar) {
        $newBar = new Bar();
        $newBar->foo = 23;

        expect($fooLens->set($bar, 23))->toEqual($newBar);
    });

    it('does not modify the original data structure', function () use ($fooLens, $bar) {
        $oldBar = $bar;

        $newBar = $fooLens->set($bar, 23);

        expect($bar)->toEqual($oldBar);
        expect($newBar)->not->toEqual($bar);
    });
});

