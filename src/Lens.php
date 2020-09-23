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

    /**
     * @template U of object
     * @template C
     * @template C
     * @param string $propertyName
     * @return Lens<U, U, C, C>
     *
     * U should contain a property $propertyName of type C
     */
    public static function objectPublicProperty(string $propertyName): self
    {
        return new self(
            (/**
             * @param U $u
             * @return C
             * @psalm-suppress MixedInferredReturnType
             * @psalm-suppress MixedReturnStatement
             */
            fn(object $u) => $u->$propertyName),
            /**
             * @param U $u
             * @param C $newC
             * @return U
             */
            function (object $u, $newC) use ($propertyName) {
                $newU = clone $u;
                $newU->$propertyName = $newC;

                return $newU;
            }
        );
    }

    /**
     * @param S $s
     * @return A
     */
    public function get($s)
    {
        return ($this->get)($s);
    }

    /**
     * @param S $s
     * @param B $b
     * @return T
     */
    public function set($s, $b)
    {
        return ($this->set)($s, $b);
    }

    /**
     * @param callable(A): B $f
     * @param S $s
     * @return T
     */
    public function update(callable $f, $s)
    {
        return $this->set($s, $f(($this->get)($s)));
    }

    /**
     * @template C
     * @template D
     * @param Lens<A, B, C, D> $that
     * @return Lens<S, T, C, D>
     */
    public function compose(Lens $that): Lens
    {
        return new self(
            (/**
             * @param S $s
             * @return C
             */
            fn($s) => $that->get(($this->get)($s))),
            (/**
             * @param S $s
             * @param D $d
             * @return T
             */
            fn($s, $d) => $this->set($s, $that->set($this->get($s), $d)))
        );
    }
}
