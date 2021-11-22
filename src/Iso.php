<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Optics;

use Marcosh\LamPHPda\Either;

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
     * @psalm-pure
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

    /**
     * @return Lens<S, T, A, B>
     */
    public function asLens(): Lens
    {
        return Lens::lens(
            $this->to,
            /**
             * @param S $_
             * @param B $b
             * @return T
             */
            fn($_, $b) => $this->from($b)
        );
    }

    /**
     * @return Prism<S, T, A, B>
     */
    public function asPrism(): Prism
    {
        return Prism::prism(
            $this->from,
            /**
             * @param S $s
             * @return Either<T, A>
             */
            fn($s) => Either::right($this->to($s))
        );
    }

    /**
     * @template C
     * @template D
     * @param Iso<A, B, C, D> $that
     * @return Iso<S, T, C, D>
     */
    public function compose(Iso $that): Iso
    {
        /** @psalm-suppress InvalidArgument */
        return new self(
            /**
             * @param S $s
             * @return C
             */
            fn($s) => $that->to($this->to($s)),
            /**
             * @param D $d
             * @return T
             */
            fn($d) => $this->from($that->from($d))
        );
    }
}
