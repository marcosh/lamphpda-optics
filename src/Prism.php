<?php

declare(strict_types=1);

namespace Marcosh\Ophptics;

use Marcosh\LamPHPda\Either;

/**
 * @template S
 * @template T
 * @template A
 * @template B
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
}
