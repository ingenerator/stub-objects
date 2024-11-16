<?php
declare(strict_types=1);

namespace test\unit\Ingenerator\StubObjects\DefaultValueGuesser;

use Ingenerator\StubObjects\Attribute\StubNullValue;
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
                'nullable_string' => StubNullValue::class,
                'nullable_int' => StubNullValue::class,
                'nullable_date' => StubNullValue::class,
                'non_null_string' => FALSE,
            ],
            $class::class,
            new StubNullValueGuesser()
        );
    }

}
