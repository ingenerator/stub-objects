<?php

namespace test\unit\Guesser\StubAs;

use DateTimeImmutable;
use Ingenerator\StubObjects\Attribute\StubAs\StubAsBackedEnum;
use Ingenerator\StubObjects\Guesser\StubAsGuesser\StubAsBackedEnumGuesser;

class StubAsBackedEnumGuesserTest extends BaseStubAsGuesserTestCase
{

    public function test_it_guesses_enum_for_backed_enums()
    {
        $class = new class {
            private $untyped;
            private string $string;
            private DateTimeImmutable $date;
            private ?MyIntEnum $nullable_int_enum;
            private MyIntEnum $no_null_int_enum;
            private ?MyStringEnum $nullable_string_enum;
            private MyStringEnum $no_null_string_enum;
        };

        $this->assertGuesses(
            [
                'untyped' => FALSE,
                'string' => FALSE,
                'date' => FALSE,
                'nullable_int_enum' => StubAsBackedEnum::class,
                'no_null_int_enum' => StubAsBackedEnum::class,
                'nullable_string_enum' => StubAsBackedEnum::class,
                'no_null_string_enum' => StubAsBackedEnum::class,
            ],
            $class::class,
            new StubAsBackedEnumGuesser()
        );
    }

}

enum MyIntEnum: int
{
    case ONE = 1;
    case TWO = 2;
}

enum MyStringEnum: string
{
    case FIRST_CASE = 'first';
    case SECOND_CASE = 'second';
}
