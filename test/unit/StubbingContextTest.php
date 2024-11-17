<?php

namespace test\unit;

use Ingenerator\StubObjects\StubbingContext;
use Ingenerator\StubObjects\StubObjects;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class StubbingContextTest extends TestCase
{


    public static function provider_is_stubbable(): array
    {
        return [
            'no patterns' => [
                [],
                [
                    'Some\Class\Name' => FALSE,
                    'Other\Class\Name' => FALSE,

                ],
            ],
            'explicit class names' => [
                [
                    'Some\Class\Name',
                    'Different\Class\Name',
                ],
                [
                    'Some\Class\Name' => TRUE,
                    'Other\Class\Name' => FALSE,
                    'Different\Class\Name' => TRUE,
                ],
            ],
            'pattern matches' => [
                [
                    'Some\*\Model',
                    'Model_*',
                ],
                [
                    'Some\Class\Model' => TRUE,
                    'Some\Other\Model' => TRUE,
                    'Some\Class\OtherThing' => FALSE,
                    'Model_One' => TRUE,
                    'Model_One_Two' => TRUE,
                    'Different\Class\Name' => FALSE,
                ],
            ],
            'sanity check with ::class refs' => [
                [
                    self::class,
                ],
                [
                    self::class => TRUE,
                ],
            ],
        ];
    }

    #[DataProvider('provider_is_stubbable')]
    public function test_it_indicates_if_a_class_is_stubbable(array $patterns, array $expect)
    {
        $subject = $this->newSubject(stubbable_class_patterns: $patterns);
        $actual = [];
        foreach (array_keys($expect) as $class) {
            $actual[$class] = $subject->isStubbable($class);
        }
        $this->assertSame($expect, $actual);
    }

    private function newSubject(array $stubbable_class_patterns = []): StubbingContext
    {
        return new StubbingContext(
            $this->createStub(StubObjects::class),
            $stubbable_class_patterns,
        );
    }
}
