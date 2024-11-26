<?php

namespace test\unit\Guesser\StubDefault;

use Ingenerator\StubObjects\Attribute\StubDefault\StubDefaultRandomString;
use Ingenerator\StubObjects\Guesser\StubDefaultGuesser\StubDefaultStringGuesser;
use Random\Engine\Mt19937;
use Random\Randomizer;

class StubDefaultStringGuesserTest extends BaseStubDefaultGuesserTestCase
{


    public function test_it_stubs_strings_as_random_values_by_default()
    {
        $class = new class {
            private string $generic_string;
            private string $other_string;
            private int $some_int;
        };

        $this->assertGuesses(
            [
                // Note these are fixed because we are seeding the randomizer
                'generic_string' => [StubDefaultRandomString::class => 'VRhXtIMMfcF2CDrm'],
                'other_string' => [StubDefaultRandomString::class => '1DZCycnj6x4fpShX'],
                'some_int' => FALSE,
            ],
            $class::class,
            new StubDefaultStringGuesser(new Randomizer(new Mt19937(1234))),
        );
    }

}
