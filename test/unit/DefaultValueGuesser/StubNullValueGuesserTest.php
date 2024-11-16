<?php
declare(strict_types=1);

namespace test\unit\DefaultValueGuesser;

use Ingenerator\StubObjects\Attribute\DefaultValue\StubNullValue;
use Ingenerator\StubObjects\DefaultValueGuesser\StubNullValueGuesser;

class StubNullValueGuesserTest extends BaseDefaultValueProviderGuesserTestCase
{


    public function test_it_guesses_null_if_nothing_else_specified()
    {
        $class = new class {
            private ?string $nullable_string;
            private ?int $nullable_int;
            private ?\DateTimeImmutable $nullable_date;
            private string $non_null_string;
        };

        $this->assertGuesses(
            [
                'nullable_string' => [StubNullValue::class => NULL],
                'nullable_int' => [StubNullValue::class => NULL],
                'nullable_date' => [StubNullValue::class => NULL],
                'non_null_string' => FALSE,
            ],
            $class::class,
            new StubNullValueGuesser()
        );
    }

}
