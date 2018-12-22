<?php

declare(strict_types=1);

namespace Marcosh\Ophptics\Lens;

final class ArrayValue extends BaseLens implements Lens
{
    public function __construct(string $key)
    {
        parent::__construct(
            function ($s) use ($key) {
                return $s[$key];
            },
            function ($s, $a) use ($key) {
                $ret = $s;

                $ret[$key] = $a;

                return $ret;
            }
        );
    }
}
