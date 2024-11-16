<?php
declare(strict_types=1);

namespace test\unit\DefaultValueGuesser;

use DateTimeImmutable;
use Ingenerator\StubObjects\Attribute\DefaultValue\StubDefaultValue;
use Ingenerator\StubObjects\DefaultValueGuesser\StubDateTimeValueGuesser;

class StubDateTimeValueGuesserTest extends BaseDefaultValueProviderGuesserTestCase
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
            new StubDateTimeValueGuesser,
        );
    }
}
