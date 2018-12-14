<?php

declare(strict_types=1);

namespace Marcosh\Ophptics;

final class ArrayValue implements Lens
{
    /**
     * @var string
     */
    private $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    /**
     * @param mixed $s : S
     * @return mixed : A
     */
    public function get($s)
    {
        return $s[$this->key];
    }

    /**
     * @param mixed $s : S
     * @param mixed $a : A
     * @return mixed
     */
    public function set($s, $a)
    {
        $ret = $s;

        $ret[$this->key] = $a;

        return $ret;
    }

    /**
     * @param Lens $that : Lens<A, B>
     * @return Lens : Lens<S, B>
     */
    public function compose(Lens $that): Lens
    {
        // TODO: Implement compose() method.
    }
}
