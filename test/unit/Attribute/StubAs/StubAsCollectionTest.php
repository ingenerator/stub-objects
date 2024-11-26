<?php

namespace test\unit\Attribute\StubAs;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ingenerator\StubObjects\Attribute\StubAs\StubAsCollection;
use Ingenerator\StubObjects\StubbingContext;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use test\TestUtils;

class StubAsCollectionTest extends TestCase
{

    #[TestWith([[], []])]
    #[TestWith([
        [['name' => 'something']],
        [new MyStubCollectionItem('_stub', 'something')],
    ])]
    #[TestWith([
        [['name' => 'something'], ['name' => 'other']],
        [
            new MyStubCollectionItem('_stub', 'something'),
            new MyStubCollectionItem('_stub', 'other'),
        ],
    ])]
    public function test_it_can_stub_as_array_of_stub_objects(array $input, array $expect): void
    {
        $attr = new StubAsCollection('array', MyStubCollectionItem::class);
        $result = $attr->cast(
            'anything',
            $input,
            $this->fakeStubbingContextToBuildMyStubCollectionItem()
        );

        $this->assertEquals($expect, $result);
    }


    #[TestWith([[], []])]
    #[TestWith([
        [['name' => 'something']],
        [new MyStubCollectionItem('_stub', 'something')],
    ])]
    #[TestWith([
        [['name' => 'something'], ['name' => 'other']],
        [
            new MyStubCollectionItem('_stub', 'something'),
            new MyStubCollectionItem('_stub', 'other'),
        ],
    ])]
    public function test_it_can_stub_as_doctrine_collection_if_doctrine_common_installed(
        array $input,
        array $expect
    ): void {
        $attr = new StubAsCollection(Collection::class, MyStubCollectionItem::class);
        $result = $attr->cast(
            'anything',
            $input,
            $this->fakeStubbingContextToBuildMyStubCollectionItem()
        );

        $this->assertEquals(new ArrayCollection($expect), $result);
    }

    public function test_it_casts_null_as_null(): void
    {
        $attr = new StubAsCollection('array', MyStubCollectionItem::class);
        $this->assertNull($attr->cast('anything', NULL, TestUtils::fakeStubbingContext()));
    }

    public function test_it_does_not_cast_existing_instance_of_collection_class(): void
    {
        $coll = new ArrayCollection(['foo' => 'bar']);
        $attr = new StubAsCollection(Collection::class, MyStubCollectionItem::class);
        $this->assertSame($coll, $attr->cast('anything', $coll, TestUtils::fakeStubbingContext()));
    }

    public function test_it_does_not_cast_existing_instances_of_item_class(): void
    {
        $item1 = new MyStubCollectionItem('test', 'something');
        $attr = new StubAsCollection('array', MyStubCollectionItem::class);
        $result = $attr->cast(
            'anything',
            [
                ['name' => 'stubbed'],
                $item1,
                ['name' => 'other'],
            ],
            $this->fakeStubbingContextToBuildMyStubCollectionItem()
        );

        $this->assertSame($item1, $result[1]);
        $this->assertEquals(
            [
                new MyStubCollectionItem(source: '_stub', name: 'stubbed'),
                $item1,
                new MyStubCollectionItem(source: '_stub', name: 'other'),
            ],
            $result
        );
    }

    private function fakeStubbingContextToBuildMyStubCollectionItem(): StubbingContext
    {
        return TestUtils::fakeStubbingContext(
            stub_objects: TestUtils::fakeStubObjects(
                function (string $class, array $values) {
                    Assert::assertSame(MyStubCollectionItem::class, $class);
                    $values['source'] = '_stub';

                    return new MyStubCollectionItem(...$values);
                }
            )
        );
    }

}

class MyStubCollectionItem
{
    public function __construct(
        public readonly string $source,
        public readonly string $name
    ) {

    }
}
