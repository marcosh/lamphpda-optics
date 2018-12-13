<?php

declare(strict_types=1);

namespace Marcosh\Ophptics;

/**
 * Interface Lens
 * @package Marcosh\Ophptics
 *
 * Lens<S, A>, where S represents the whole and A represents the part
 */
interface Lens
{
    /**
     * @param mixed $s : S
     * @return mixed : A
     */
    public function get($s);

    /**
     * @param mixed $s : S
     * @param mixed $a : A
     * @return mixed
     */
    public function set($s, $a);

    /**
     * @param Lens $that : Lens<A, B>
     * @return Lens : Lens<S, B>
     */
    public function compose(Lens $that): Lens;
}
