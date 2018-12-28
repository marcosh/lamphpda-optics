<?php

declare(strict_types=1);

namespace Marcosh\Ophptics\Prism;

/**
 * Interface Prism
 * @package Marcosh\Ophptics\Prism
 *
 * Prism<S, A> where S represents the whole and A the optional part
 */
interface Prism
{
    /**
     * @param mixed $a : A
     * @return mixed : S
     */
    public function build($a);

    /**
     * @param mixed $s : S
     * @return mixed : A|null
     */
    public function maybeGet($s);

    /**
     * @param mixed $s : S
     * @param callable $f : A -> A
     * @return mixed : S
     */
    public function modify($s, callable $f);

    /**
     * @param Prism $that : Prism<A, B>
     * @return Prism : Prism<S, B>
     */
    public function compose(Prism $that): Prism;
}
