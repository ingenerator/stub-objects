<?php

namespace test\unit\Attribute\StubAs;

use BackedEnum;
use Ingenerator\StubObjects\Attribute\StubAs\StubAsBackedEnum;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use test\TestUtils;
use ValueError;

class StubAsBackedEnumTest extends TestCase
{

    #[TestWith([NULL, NULL])]
    #[TestWith([MyIntEnum::ONE, MyIntEnum::ONE])]
    #[TestWith([MyStringEnum::FIRST_CASE, MyStringEnum::FIRST_CASE])]
    public function test_it_returns_original_if_null_or_already_an_instance(mixed $input, mixed $expect): void
    {
        // Note the type is not relevant here
        $attr = new StubAsBackedEnum(MyStringEnum::class);
        $result = $attr->cast('anything', $input, TestUtils::fakeStubbingContext());
        $this->assertSame($expect, $result);
    }

    #[TestWith([MyIntEnum::class, 1, MyIntEnum::ONE])]
    #[TestWith([MyIntEnum::class, 2, MyIntEnum::TWO])]
    #[TestWith([MyStringEnum::class, 'first', MyStringEnum::FIRST_CASE])]
    #[TestWith([MyStringEnum::class, 'second', MyStringEnum::SECOND_CASE])]
    public function test_it_casts_from_valid_backing_value(
        string $as_class,
        int|string $input,
        BackedEnum $expect
    ): void {
        $attr = new StubAsBackedEnum($as_class);
        $result = $attr->cast('anything', $input, TestUtils::fakeStubbingContext());
        $this->assertSame($expect, $result);
    }

    public function test_it_bubbles_exception_from_invalid_backing_value(): void
    {
        $attr = new StubAsBackedEnum(MyStringEnum::class);
        $this->expectException(ValueError::class);
        $this->expectExceptionMessage('junk');
        $attr->cast('whatever', 'junk', TestUtils::fakeStubbingContext());
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
