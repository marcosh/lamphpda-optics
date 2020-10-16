<?php

declare(strict_types=1);

namespace Marcosh\OphpticsSpec;

use Marcosh\LamPHPda\Either;
use Marcosh\Ophptics\Prism;

describe('prisms composition', function () {
    // consider a data structure with nested `Either`s as `Either<int, Either<string, float>>`
    // we would like to focus on the`string` component

    /** @var Prism<Either<string, float>, Either<string, float>, string, string> $innerPrism */
    $innerPrism = Prism::left();

    /** @var Prism<Either<int, Either<string, float>>, Either<int, Either<string, float>>, Either<string, float>, Either<string, float>> $outerPrism */
    $outerPrism = Prism::right();

    $composedPrism = $outerPrism->compose($innerPrism);

    it('allows to review', function () use ($composedPrism) {
        expect($composedPrism->review("hello"))->toEqual(Either::right(Either::left("hello")));
    });

    it('allows to preview the string', function () use ($composedPrism) {
        expect($composedPrism->preview(Either::right(Either::left("hello"))))->toEqual(Either::right("hello"));
    });

    it('does not retrieve the string if it was not in the inner Either', function () use ($composedPrism) {
        $notAString = Either::right(Either::right(12.34));

        expect($composedPrism->preview($notAString))->toEqual(Either::left($notAString));
    });

    it('does not retrieve the string if it was not in the outer Either', function () use ($composedPrism) {
        $notAString = Either::left(42);

        expect($composedPrism->preview($notAString))->toEqual(Either::left($notAString));
    });
});
