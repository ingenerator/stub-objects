<?php

namespace test\integration;

use Ingenerator\StubObjects\Attribute\StubDefault\StubDefaultValue;
use Ingenerator\StubObjects\Attribute\StubMergeDefaultsWith;
use Ingenerator\StubObjects\StubObjects;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CustomDefaultMergingTest extends TestCase
{

    public static function doCustomMerge(array $defaults, array $values): array
    {
        $status = $values['status'] ?? $defaults['status'];
        $status_extra_defaults = match ($status) {
            'draft' => [],
            'submitted' => [
                'submitted_by' => 'Brian',
            ],
            'paid' => [
                'submitted_by' => 'Brian',
                'paid_by' => 'James',
            ]
        };

        return [
            ...$defaults,
            ...$status_extra_defaults,
            ...$values,
        ];
    }

    public static function provider_merge_custom(): array
    {
        return [
            'empty' => [
                [],
                ['status' => 'draft', 'submitted_by' => NULL, 'paid_by' => NULL],
            ],
            'draft with custom' => [
                ['submitted_by' => 'Phil'],
                ['status' => 'draft', 'submitted_by' => 'Phil', 'paid_by' => NULL],
            ],
            'submitted' => [
                ['status' => 'submitted'],
                ['status' => 'submitted', 'submitted_by' => 'Brian', 'paid_by' => NULL],
            ],
            'submitted with custom' => [
                ['status' => 'submitted', 'submitted_by' => 'Phil', 'paid_by' => 'Brian'],
                ['status' => 'submitted', 'submitted_by' => 'Phil', 'paid_by' => 'Brian'],
            ],
            'paid' => [
                ['status' => 'paid'],
                ['status' => 'paid', 'submitted_by' => 'Brian', 'paid_by' => 'James'],
            ],
            'paid with custom' => [
                ['status' => 'paid', 'submitted_by' => 'Phil', 'paid_by' => 'Brian'],
                ['status' => 'paid', 'submitted_by' => 'Phil', 'paid_by' => 'Brian'],
            ],
        ];

    }

    #[DataProvider('provider_merge_custom')]
    public function test_classes_can_customise_merging_defaults_and_values(array $values, array $expect)
    {
        $actual = $this->newSubject()->stub(MyObjectWithCustomMerging::class, $values);
        $this->assertSame($expect, (array) $actual);
    }


    private function newSubject(array $stubbable_class_patterns = ['*']): StubObjects
    {
        return new StubObjects(...get_defined_vars());
    }
}

#[StubMergeDefaultsWith([CustomDefaultMergingTest::class, 'doCustomMerge'])]
class MyObjectWithCustomMerging
{

    #[StubDefaultValue('draft')]
    public string $status;
    public ?string $submitted_by;
    public ?string $paid_by;
}
