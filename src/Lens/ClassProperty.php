<?php

declare(strict_types=1);

namespace Marcosh\Ophptics\Lens;

final class ClassProperty extends BaseLens implements Lens
{
    public function __construct(string $property)
    {
        parent::__construct(
            function ($s) use ($property) {
                return $s->$property;
            },
            function ($s, $a) use ($property) {
                $ret = clone $s;

                $ret->$property = $a;

                return $ret;
            }
        );
    }
}
