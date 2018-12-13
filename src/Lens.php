<?php

declare(strict_types=1);

namespace Marcosh\Ophptics;

/**
 * Class Lens
 * @package Marcosh\Ophptics
 *
 * Lens<S, A>, where S represents the whole and A represents the part
 */
final class Lens implements LensInterface
{
    /**
     * @var callable : S -> A
     */
    private $get;

    /**
     * @var callable : S -> A -> A
     */
    private $set;

    /**
     * Lens constructor.
     * @param callable $get : S -> A
     * @param callable $set : S -> A -> A
     */
    public function __construct(
        callable $get,
        callable $set
    ) {
        $this->get = $get;
        $this->set = $set;
    }

    /**
     * @param mixed $s : S
     * @return mixed : A
     */
    public function get($s)
    {
        return ($this->get)($s);
    }

    /**
     * @param mixed $s : S
     * @param mixed $a : A
     * @return mixed
     */
    public function set($s, $a)
    {
        return ($this->set)($s, $a);
    }

    /**
     * @param LensInterface $that : LensInterface<A, B>
     * @return LensInterface : Lens<S, B>
     */
    public function compose(LensInterface $that): LensInterface
    {
        return new self(
            function ($s) use ($that) {
                return $that->get($this->get($s));
            },
            function ($s, $b) use ($that) {
                return $this->set($s, $that->set($this->get($s), $b));
            }
        );
    }
}
