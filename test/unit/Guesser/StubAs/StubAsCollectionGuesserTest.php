<?php

namespace test\unit\Guesser\StubAs;

use Doctrine\Common\Collections\Collection;
use Ingenerator\StubObjects\Attribute\StubAs\StubAsCollection;
use Ingenerator\StubObjects\Guesser\StubAsGuesser\StubAsCollectionGuesser;
use test\TestUtils;

class StubAsCollectionGuesserTest extends BaseStubAsGuesserTestCase
{

    public function test_it_guesses_how_to_hydrate_collections()
    {
        $class = new class {
            private string $string;

            /**
             * @var Collection<MyChildObject>
             */
            private Collection $stubbable_collection;

        };

        $this->assertGuesses(
            [
                'string' => FALSE,
                'stubbable_collection' => StubAsCollection::class,
            ],
            $class::class,
            new StubAsCollectionGuesser(),
            TestUtils::fakeStubbingContext(stubbable_class_patterns: [MyChildObject::class])
        );

        // @todo assert the args that went to the attribute
    }

    // @todo throw if it can't parse the collection type or the child is not stubbable
}
