<?php

namespace Ingenerator\StubObjects\Attribute\StubAs;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ingenerator\StubObjects\Attribute\StubAs;
use Ingenerator\StubObjects\StubbingContext;

class StubAsCollection implements StubAs
{
    public function __construct(
        private readonly string $collection_class,
        private readonly string $item_class,
    ) {

    }

    public function cast(string $property, mixed $value, StubbingContext $context): mixed
    {
        // @todo tests etc for this
        // @todo also need an integration test covering this
        if (($value === NULL) || ($value instanceof $this->collection_class)) {
            return $value;
        }

        $items = array_map(
            fn($v) => $v instanceof $this->item_class ? $v : $context->stub_objects->stub($this->item_class, $v),
            $value
        );

        return match ($this->collection_class) {
            Collection::class => new ArrayCollection($items)
        };
    }

}
