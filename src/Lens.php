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
     * @return mixed : S
     */
    public function set($s, $a);

    /**
     * @param mixed $s : S
     * @param callable $f : A -> A
     * @return mixed : S
     */
    public function modify($s, callable $f);

    /**
     * @param Lens $that : Lens<A, B>
     * @return Lens : Lens<S, B>
     */
    public function compose(Lens $that): Lens;
}
