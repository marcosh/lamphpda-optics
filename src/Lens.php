<?php

declare(strict_types=1);

namespace Marcosh\Ophptics;

/**
 * @template S
 * @template T
 * @template A
 * @template B
 */
final class Lens
{
    /**
     * @var callable(S): A
     */
    private $get;

    /**
     * @var callable(S, B): T
     */
    private $set;

    /**
     * @param callable(S): A $get
     * @param callable(S, B): T $set
     */
    private function __construct(callable $get, callable $set)
    {
        $this->get = $get;
        $this->set = $set;
    }

    /**
     * @template U
     * @template V
     * @template C
     * @template D
     * @param callable(U): C $get
     * @param callable(U, D): V $set
     * @return self<U, V, C, D>
     */
    public static function lens(callable $get, callable $set): self
    {
        return new self($get, $set);
    }
}
