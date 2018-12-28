<?php

declare(strict_types=1);

namespace Marcosh\OphpticsSpec\Prism;

use Marcosh\Ophptics\Prism\BasePrism;

class Person
{
    /**
     * @var Person|null
     */
    private $firstSon;

    public function __construct(?Person $firstSon)
    {
        $this->firstSon = $firstSon;
    }

    public function firstSon(): ?Person
    {
        return $this->firstSon;
    }
}

$firstSonPrism = new BasePrism(
    function (Person $firstSon) {
        return new Person($firstSon);
    },
    function (Person $person) {
        return $person->firstSon();
    }
);

describe('FirstSonPrism', function () use ($firstSonPrism) {
    $son = new Person(null);

    it('builds the parent correctly', function () use ($firstSonPrism, $son) {
        expect($firstSonPrism->build($son))->toEqual(new Person($son));
    });

    it('gets the son correctly', function () use ($firstSonPrism, $son) {
        expect($firstSonPrism->maybeGet(new Person($son)))->toBe($son);
    });

    it('returns null is the son is not present', function () use ($firstSonPrism, $son) {
        expect($firstSonPrism->maybeGet($son))->toBeNull();
    });

    it('modifies the internal value via a callable', function () use ($firstSonPrism, $son) {
        $daughter = new Person($son);
        $parent = new Person($son);
        $oldParent = clone $parent;

        $f = function (Person $p) use ($daughter) {
            return $daughter;
        };

        expect($firstSonPrism->modify($parent, $f))->toEqual(new Person($daughter));
        expect($parent)->toEqual($oldParent);
    });
});
