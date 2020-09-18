<?php

declare(strict_types=1);

namespace Marcosh\OphpticsSpec;

use Marcosh\Ophptics\Lens;

class Foo
{
}

class Bar
{
    /** @var Foo */
    public $foo;

    public function __construct(Foo $foo)
    {
        $this->foo = $foo;
    }
}

class Baz
{
    /** @var Bar */
    public $bar;

    public function __construct(Bar $bar)
    {
        $this->bar = $bar;
    }
}

describe('lens composition', function () {
    it('allows to access nested properties', function () {
        $fooBar = Lens::lens(
            (fn($bar) => $bar->foo),
            function (Bar $bar, Foo $otherFoo) {
                $otherBar = clone $bar;

                $otherBar->foo = $otherFoo;

                return $otherBar;
            }
        );
        $barBaz = Lens::lens(
            (fn($baz) => $baz->bar),
            function (Baz $baz, Bar $otherBar) {
                $otherBaz = clone $baz;

                $otherBaz->bar = $otherBar;

                return $otherBaz;
            }
        );

        $fooBaz = $barBaz->compose($fooBar);

        $foo = new Foo();
        $bar = new Bar($foo);
        $baz = new Baz($bar);

        expect($fooBaz->get($baz))->toBe($foo);

        $newFoo = new Foo();

        expect($fooBaz->set($baz, $newFoo)->bar->foo)->toBe($newFoo);
    });
});
