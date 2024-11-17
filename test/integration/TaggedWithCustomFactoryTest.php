<?php
declare(strict_types=1);

namespace test\integration;

use Ingenerator\StubObjects\Attribute\StubFactory;
use Ingenerator\StubObjects\StubObjects;
use PHPUnit\Framework\TestCase;

class TaggedWithCustomFactoryTest extends TestCase
{

    public function test_it_can_create_instance_of_class_tagged_with_custom_factory_attribute()
    {
        $result = $this->newSubject()->stub(
            MyEntityWithCustomFactory::class,
            ['param1' => 'Something', 'param2' => 3],
        );

        $this->assertInstanceOf(MyEntityWithCustomFactory::class, $result);
        $this->assertSame(['param1' => 'Something', 'param2' => 3], (array) $result);
    }

    private function newSubject(): StubObjects
    {
        return new StubObjects();
    }

}

class StubMyEntity
{
    public static function with(array $vars): MyEntityWithCustomFactory
    {
        return new MyEntityWithCustomFactory(...$vars);
    }
}

#[StubFactory([StubMyEntity::class, 'with'])]
readonly class MyEntityWithCustomFactory
{
    public function __construct(
        public string $param1,
        public int $param2
    ) {
    }
}
