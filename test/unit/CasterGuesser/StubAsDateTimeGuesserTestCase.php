<?php

namespace test\unit\CasterGuesser;

use DateTimeImmutable;
use Ingenerator\StubObjects\Attribute\Caster\StubAsDateTime;
use Ingenerator\StubObjects\CasterGuesser\StubAsDateTimeGuesser;

class StubAsDateTimeGuesserTestCase extends BaseCasterGuesserTestCase
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
                'string' => false,
                'non_null_date' => StubAsDateTime::class,
                'nullable_date' => StubAsDateTime::class,
            ],
            $class::class,
            new StubAsDateTimeGuesser()
        );
    }
}
