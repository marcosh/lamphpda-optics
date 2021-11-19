<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\OpticsSpec;

use Marcosh\LamPHPda\Optics\Lens;

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
        $fooBar = Lens::objectPublicProperty('foo');
        $barBaz = Lens::objectPublicProperty('bar');

        $fooBaz = $barBaz->compose($fooBar);

        $foo = new Foo();
        $bar = new Bar($foo);
        $baz = new Baz($bar);

        expect($fooBaz->get($baz))->toBe($foo);

        $newFoo = new Foo();

        expect($fooBaz->set($baz, $newFoo)->bar->foo)->toBe($newFoo);
    });
});
