<?php

namespace test\unit\DefaultValueGuesser;

use Ingenerator\StubObjects\Attribute\DefaultValue\StubRandomString;
use Ingenerator\StubObjects\DefaultValueGuesser\StubStringValueGuesser;
use Random\Engine\Mt19937;
use Random\Randomizer;

class StubStringValueGuesserTest extends BaseDefaultValueProviderGuesserTestCase
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
                'generic_string' => [StubRandomString::class => 'VRhXtIMMfcF2CDrm'],
                'other_string' => [StubRandomString::class => '1DZCycnj6x4fpShX'],
                'some_int' => FALSE,
            ],
            $class::class,
            new StubStringValueGuesser(new Randomizer(new Mt19937(1234))),
        );
    }

}
