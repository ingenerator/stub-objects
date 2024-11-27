<?php

namespace Ingenerator\StubObjects;

use ReflectionNamedType;
use ReflectionProperty;

class ReflectionUtils
{

    public static function isBuiltinType(ReflectionProperty $property): bool
    {
        $type = $property->getType();

        return ($type instanceof ReflectionNamedType) && $type->isBuiltin();
    }

    public static function getTypeNameIfAvailable(ReflectionProperty $property): false|string
    {
        $type = $property->getType();
        if ($type instanceof \ReflectionNamedType) {
            return $type->getName();
        }

        return FALSE;
    }
}
