<?php
declare(strict_types=1);

namespace test\unit\Guesser\StubDefault;

use DateTimeImmutable;
use Ingenerator\StubObjects\Attribute\StubDefault\StubDefaultValue;
use Ingenerator\StubObjects\Guesser\StubDefaultGuesser\StubDefaultDateTimeGuesser;

class StubDefaultDateTimeGuesserTest extends BaseStubDefaultGuesserTestCase
{
    public function test_it_guesses_current_time()
    {
        $class = new class {
            private DateTimeImmutable $date_time;
            private string $string;
        };

        $this->assertGuesses(
            [
                'date_time' => [StubDefaultValue::class => 'now'],
                'string' => FALSE,
            ],
            $class::class,
            new StubDefaultDateTimeGuesser,
        );
    }
}
