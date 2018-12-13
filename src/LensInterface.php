<?php

declare(strict_types=1);

namespace Marcosh\Ophptics;

/**
 * Interface LensInterface
 * @package Marcosh\Ophptics
 *
 * LensInterface<S, A>, where S represents the whole and A represents the part
 */
interface LensInterface
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
     * @param LensInterface $that : LensInterface<A, B>
     * @return LensInterface : LensInterface<S, B>
     */
    public function compose(LensInterface $that): LensInterface;
}
