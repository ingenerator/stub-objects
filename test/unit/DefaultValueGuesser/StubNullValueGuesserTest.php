<?php
declare(strict_types=1);

namespace test\unit\Ingenerator\StubObjects\DefaultValueGuesser;

use Ingenerator\StubObjects\Attribute\StubNullValue;
use Ingenerator\StubObjects\DefaultValueGuesser\StubNullValueGuesser;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

class StubNullValueGuesserTest extends TestCase
{

    #[TestWith(['nullable_string', StubNullValue::class])]
    #[TestWith(['nullable_int', StubNullValue::class])]
    #[TestWith(['nullable_date', StubNullValue::class])]
    #[TestWith(['non_null_string', NULL])]
    public function test_it_guesses_null_if_nothing_else_specified(string $property, ?string $expect_guess)
    {
        $class = new class {
            private ?string $nullable_string;
            private ?int $nullable_int;
            private ?\DateTimeImmutable $nullable_date;
            private string $non_null_string;
        };

        $reflection = new \ReflectionClass($class);
        $actual_guess = (new StubNullValueGuesser)->guessProvider($reflection->getProperty($property));

        if ($actual_guess) {
            $actual_guess = $actual_guess::class;
        }

        $this->assertSame($expect_guess, $actual_guess);
    }

}
