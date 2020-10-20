<?php

declare(strict_types=1);

use Marcosh\Ophptics\Iso;

describe('iso composition', function () {
    it('allows to morph between different representations', function () {
        // converts between array{foo: string} and array{bar: string}
        $iso1 = Iso::iso(
            fn($s) => ['bar' => $s['foo']],
            fn($b) => ['foo' => $b['bar']]
        );

        // converts between array{bar: string} and array{baz: string}
        $iso2 = Iso::iso(
            fn($s) => ['baz' => $s['bar']],
            fn($b) => ['bar' => $b['baz']]
        );

        $iso = $iso1->compose($iso2);

        expect($iso->to(['foo' => 'Hello!']))->toEqual(['baz' => 'Hello!']);
        expect($iso->from(['baz' => 'Hello!']))->toEqual(['foo' => 'Hello!']);
    });
});
