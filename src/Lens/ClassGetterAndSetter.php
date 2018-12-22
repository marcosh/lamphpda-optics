<?php

declare(strict_types=1);

namespace Marcosh\Ophptics\Lens;

final class ClassGetterAndSetter extends BaseLens
{
    public function __construct(string $getter, string $setter)
    {
        parent::__construct(
            function ($s) use ($getter) {
                return $s->$getter();
            },
            function ($s, $a) use ($setter) {
                return $s->$setter($a);
            }
        );
    }
}
