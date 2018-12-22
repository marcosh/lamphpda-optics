<?php

declare(strict_types=1);

namespace Marcosh\Ophptics\Lens;

/**
 * Class BaseLens
 * @package Marcosh\Ophptics
 *
 * BaseLens<S, A>, where S represents the whole and A represents the part
 */
class BaseLens implements Lens
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
     * @return mixed : S
     */
    public function set($s, $a)
    {
        return ($this->set)($s, $a);
    }

    /**
     * @param mixed $s : S
     * @param callable $f : A -> A
     * @return mixed : S
     */
    public function modify($s, callable $f)
    {
        return ($this->set)($s, $f(($this->get)($s)));
    }

    /**
     * @param Lens $that : Lens<A, B>
     * @return Lens : Lens<S, B>
     */
    public function compose(Lens $that): Lens
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
