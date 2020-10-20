<?php

declare(strict_types=1);

namespace Marcosh\Ophptics;

use Marcosh\LamPHPda\Either;

/**
 * @template S
 * @template T
 * @template A
 * @template B
 * @psalm-immutable
 */
final class Prism
{
    /** @var callable(B): T */
    private $review;

    /** @var callable(S): Either<T, A> */
    private $preview;

    /**
     * @param callable(B): T $review
     * @param callable(S): Either<T, A> $preview
     */
    private function __construct(callable $review, callable $preview)
    {
        $this->review = $review;
        $this->preview = $preview;
    }

    /**
     * @template U
     * @template V
     * @template C
     * @template D
     * @param callable(D): V $review
     * @param callable(U): Either<V, C> $preview
     */
    public static function prism(callable $review, callable $preview): self
    {
        return new self($review, $preview);
    }

    /**
     * @template C
     * @template D
     * @template E
     * @return Prism<Either<C, E>, Either<D, E>, C, D>
     */
    public static function left(): self
    {
        return new self(
            /**
             * @param D $d
             * @return Either<D, E>
             */
            fn($d) => Either::left($d),
            /**
             * @param Either<C, E> $eitherCE
             * @return Either<Either<D, E>, C>
             */
            function (Either $eitherCE) {
                return $eitherCE->eval(
                    /**
                     * @param C $c
                     * @return Either<Either<D, E>, C>
                     */
                    fn($c) => Either::right($c),
                    /**
                     * @param E $e
                     * @return Either<Either<D, E>, C>
                     */
                    function ($e) {
                        /** @var Either<D, E> $eitherDE */
                        $eitherDE = Either::right($e);

                        return Either::left($eitherDE);
                    }
                );
            }
        );
    }

    /**
     * @template C
     * @template D
     * @template E
     * @return Prism<Either<E, C>, Either<E, D>, C, D>
     */
    public static function right(): self
    {
        return new self(
            /**
             * @param D $d
             * @return Either<E, D>
             */
            fn($d) => Either::right($d),
            /**
             * @param Either<E, C> $eitherEC
             * @return Either<Either<E, D>, C>
             */
            function (Either $eitherEC) {
                return $eitherEC->eval(
                    /**
                     * @param E $e
                     * @return Either<Either<E, D>, C>
                     */
                    function ($e) {
                        /** @var Either<E, D> $eitherED */
                        $eitherED = Either::left($e);

                        return Either::left($eitherED);
                    },
                    /**
                     * @param C $c
                     * @return Either<Either<E, D>, C>
                     */
                    fn($c) => Either::right($c)
                );
            }
        );
    }

    /**
     * @param B $b
     * @return T
     */
    public function review($b)
    {
        return ($this->review)($b);
    }

    /**
     * @param S $s
     * @return Either<T, A>
     */
    public function preview($s): Either
    {
        return ($this->preview)($s);
    }

    /**
     * @template C
     * @template D
     * @param Prism<A, B, C, D> $that
     * @return Prism<S, T, C, D>
     */
    public function compose(Prism $that): Prism
    {
        return new self(
            (/**
             * @param D $d
             * @return T
             */
            fn($d) => $this->review($that->review($d))),
            (/**
             * @param S $s
             * @return Either<T, C>
             */
            fn($s) => $this->preview($s)->bind(
                /**
                 * @param A $a
                 * @return Either<T, C>
                 *
                 * @psalm-suppress InvalidArgument //TODO: this should be removed as soon as
                 * https://github.com/vimeo/psalm/issues/4326 is solved
                 */
                fn($a) => $that->preview($a)->mapLeft($this->review)
            ))
        );
    }
}
