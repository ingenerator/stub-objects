<?php
declare(strict_types=1);

namespace test\integration\Ingenerator\StubObjects;

use DateTimeImmutable;
use Ingenerator\StubObjects\Attribute\StubDefaultValue;
use Ingenerator\StubObjects\Attribute\StubSequentialId;
use Ingenerator\StubObjects\StubObjectFactory;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class DefaultPropertyValuesTest extends TestCase
{

    public static function provider_create_with_defaults(): array
    {
        return [
            'nothing specified, use class defaults' => [
                [],
                [
                    'ok' => FALSE,
                    'anything' => 'new',
                ],
            ],
            'partial override specified, merge in' => [
                ['ok' => TRUE],
                [
                    'ok' => TRUE,
                    'anything' => 'new',
                ],
            ],
            'full override specified' => [
                ['ok' => TRUE, 'anything' => 'else'],
                ['ok' => TRUE, 'anything' => 'else'],
            ],
        ];
    }

    #[DataProvider('provider_create_with_defaults')]
    public function test_it_can_create_object_with_predefined_default_properties_and_overrides(
        array $values,
        array $expect
    ) {
        $class = new class {
            private bool $ok = FALSE;
            private string $anything = 'new';

            public function getProps()
            {
                return get_object_vars($this);
            }
        };

        $result = $this->newSubject()->stub($class::class, $values);
        $this->assertInstanceOf($class::class, $result);
        $this->assertSame($expect, $result->getProps());
    }

    public static function provider_default_null(): array
    {
        $dt = new DateTimeImmutable();

        return [
            'nothing specified, all null' => [
                [],
                [
                    'might_have_string' => NULL,
                    'might_have_int' => NULL,
                    'might_have_date' => NULL,
                    'has_default' => 'not forced to null',
                ],
            ],
            'can override' => [
                ['might_have_date' => $dt, 'might_have_int' => 152],
                [
                    'might_have_string' => NULL,
                    'might_have_int' => 152,
                    'might_have_date' => $dt,
                    'has_default' => 'not forced to null',
                ],
            ],
        ];
    }

    #[DataProvider('provider_default_null')]
    public function test_it_defaults_nullable_properties_to_null_even_if_no_class_default(array $values, array $expect)
    {
        $class = new class {
            private ?string $might_have_string;
            private ?int $might_have_int;
            private ?DateTimeImmutable $might_have_date;
            private ?string $has_default = 'not forced to null';

            public function getProps()
            {
                return get_object_vars($this);
            }
        };

        $result = $this->newSubject()->stub($class::class, $values);
        $this->assertInstanceOf($class::class, $result);
        $this->assertSame($expect, $result->getProps());
    }

    public function test_it_can_default_properties_to_a_sequential_id()
    {
        $class = new readonly class {
            #[StubSequentialId]
            public ?int $id;

            #[StubSequentialId]
            public int $other_id;

            public ?int $other_number;
        };

        $result1 = $this->newSubject()->stub($class::class, []);

        $this->assertSame(NULL, $result1->other_number, 'Should not affect other numeric fields');
        $this->assertSame($result1->id + 1, $result1->other_id, 'Should allocate sequential numbers');

        // Note that the id sequence is unique even across different instances
        $result2 = $this->newSubject()->stub($class::class, ['other_id' => 2, 'other_number' => 15]);
        $this->assertSame(
            [
                'id' => $result1->other_id + 1,
                'other_id' => 2,
                'other_number' => 15,
            ],
            (array) $result2
        );
    }

    public function test_it_can_default_properties_to_a_fixed_value()
    {
        $class = new readonly class {
            #[StubDefaultValue('whatever')]
            public ?string $status;
        };

        $this->assertSame(
            ['status' => 'whatever'],
            (array) $this->newSubject()->stub($class::class, [])
        );

        $this->assertSame(
            ['status' => 'foo'],
            (array) $this->newSubject()->stub($class::class, ['status' => 'foo'])
        );
    }


    private function newSubject(): StubObjectFactory
    {
        return new StubObjectFactory();
    }
}
