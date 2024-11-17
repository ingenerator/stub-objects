<?php
declare(strict_types=1);

namespace test\unit\Guesser\StubDefault;

use Ingenerator\StubObjects\Attribute\StubDefault\StubDefaultNull;
use Ingenerator\StubObjects\Guesser\StubDefaultGuesser\StubDefaultNullGuesser;

class StubDefaultNullGuesserTest extends BaseStubDefaultGuesserTestCase
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
                'nullable_string' => [StubDefaultNull::class => NULL],
                'nullable_int' => [StubDefaultNull::class => NULL],
                'nullable_date' => [StubDefaultNull::class => NULL],
                'non_null_string' => FALSE,
            ],
            $class::class,
            new StubDefaultNullGuesser()
        );
    }

}
