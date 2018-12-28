<?php

declare(strict_types=1);

namespace Marcosh\Ophptics\Prism;

/**
 * Class BasePrism
 * @package Marcosh\Ophptics\Prism
 *
 * BasePrism<S, A>, where S represents the whole and A represents the part
 */
class BasePrism implements Prism
{
    /**
     * @var callable : A -> S
     */
    private $build;

    /**
     * @var callable : S -> A|null
     */
    private $maybeGet;

    /**
     * BasePrism constructor.
     * @param callable $build : A -> S
     * @param callable $maybeGet : S -> A|null
     */
    public function __construct(
        callable $build,
        callable $maybeGet
    ) {
        $this->build = $build;
        $this->maybeGet = $maybeGet;
    }

    /**
     * @param mixed $a : A
     * @return mixed : S
     */
    public function build($a)
    {
        return ($this->build)($a);
    }

    /**
     * @param mixed $s : S
     * @return mixed : A|null
     */
    public function maybeGet($s)
    {
        return ($this->maybeGet)($s);
    }

    /**
     * @param mixed $s : S
     * @param callable $f : A -> A
     * @return mixed : S
     */
    public function modify($s, callable $f)
    {
        $maybeA = ($this->maybeGet)($s);

        if (null === $maybeA) {
            return $s;
        }

        return ($this->build)($f($maybeA));
    }

    /**
     * @param Prism $that : Prism<A, B>
     * @return Prism : Prism<S, B>
     */
    public function compose(Prism $that): Prism
    {
        return new self(
            function ($b) use ($that) {
                return ($this->build)($that->build($b));
            },
            function ($s) use ($that) {
                $maybeA = ($this->maybeGet)($s);

                if (null === $maybeA) {
                    return null;
                }

                return $that->maybeGet($maybeA);
            }
        );
    }
}
