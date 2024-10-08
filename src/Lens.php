<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Optics;

/**
 * @template S
 * @template T
 * @template A
 * @template B
 * @psalm-immutable
 */
final class Lens
{
    /**
     * @var pure-callable(S): A
     */
    private $get;

    /**
     * @var pure-callable(S, B): T
     */
    private $set;

    /**
     * @param pure-callable(S): A $get
     * @param pure-callable(S, B): T $set
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
     * @param pure-callable(U): C $get
     * @param pure-callable(U, D): V $set
     * @return self<U, V, C, D>
     * @psalm-pure
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
     * @psalm-pure
     *
     * U should contain a property $propertyName of type C
     */
    public static function objectPublicProperty(string $propertyName): self
    {
        /**
         * @var Lens<U, U, C, C>
         * @psalm-suppress InvalidArgument
         */
        return new self(
            /**
             * @param U $u
             * @return C
             * @psalm-suppress MixedInferredReturnType
             * @psalm-suppress MixedReturnStatement
             */
            fn(object $u) => $u->$propertyName,
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
     * @template C
     * @template D
     * @param array-key $arrayKey
     * @return Lens<array, array, C, D>
     *
     * array should contain a key $arrayKey of type C
     */
    public static function arrayKey($arrayKey): self
    {
        /** @var Lens<array, array, C, D> */
        return new self(
            /**
             * @return C
             *
             * @psalm-suppress MixedInferredReturnType, MixedReturnStatement
             */
            fn(array $a) => $a[$arrayKey],
            /**
             * @param D $d
             * @return array
             */
            function (array $a, $d) use ($arrayKey) {
                $a[$arrayKey] = $d;

                return $a;
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
     * @param pure-callable(A): B $f
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
        /** @psalm-suppress InvalidArgument */
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
