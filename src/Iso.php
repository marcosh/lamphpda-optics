<?php

declare(strict_types=1);

namespace Marcosh\Ophptics;

/**
 * @template S
 * @template T
 * @template A
 * @template B
 * @psalm-immutable
 */
final class Iso
{
    /** @var callable(S): A */
    private $to;

    /** @var callable(B): T */
    private $from;

    /**
     * @param callable(S): A $to
     * @param callable(B): T $from
     */
    private function __construct(callable $to, callable $from)
    {
        $this->to = $to;
        $this->from = $from;
    }

    /**
     * @template U
     * @template V
     * @template C
     * @template D
     * @param callable(U): C $to
     * @param callable(D): V $from
     * @return Iso<U, V, C, D>
     */
    public static function iso(callable $to, callable $from): self
    {
        return new self($to, $from);
    }

    /**
     * @param S $s
     * @return A
     */
    public function to($s)
    {
        return ($this->to)($s);
    }

    /**
     * @param B $b
     * @return T
     */
    public function from($b)
    {
        return ($this->from)($b);
    }

    /**
     * @return Iso<B, A, T, S>
     */
    public function inverse(): self
    {
        return new self($this->from, $this->to);
    }
}
