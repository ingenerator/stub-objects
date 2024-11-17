<?php

namespace test\unit\Guesser\StubAs;

use DateTimeImmutable;
use Ingenerator\StubObjects\Attribute\StubAs\StubAsDateTime;
use Ingenerator\StubObjects\Guesser\StubAsGuesser\StubAsDateTimeGuesser;

class StubAsDateTimeGuesserTest extends BaseStubAsGuesserTestCase
{

    public function test_it_guesses_date_time_for_date_time_props()
    {
        $class = new class {
            private string $string;
            private DateTimeImmutable $non_null_date;
            private ?DateTimeImmutable $nullable_date;
        };

        $this->assertGuesses(
            [
                'string' => FALSE,
                'non_null_date' => StubAsDateTime::class,
                'nullable_date' => StubAsDateTime::class,
            ],
            $class::class,
            new StubAsDateTimeGuesser()
        );
    }
}
