<?php

namespace Ingenerator\StubObjects;

final class StubbingContext
{

    private array $is_stubbable_cache = [];

    public function __construct(
        public readonly StubObjects $stub_objects,
        private readonly array $stubbable_class_patterns,
    ) {

    }

    public function isStubbable(string $class): bool
    {
        $this->is_stubbable_cache[$class] ??= $this->calculateIsStubbable($class);

        return $this->is_stubbable_cache[$class];
    }

    private function calculateIsStubbable(string $class): bool
    {
        foreach ($this->stubbable_class_patterns as $pattern) {
            // Using fnmatch rather than preg_match to restrict the available pattern match syntax and avoid the need
            // to escape `\` in names (which would be hard to do with Some::class dynamic references)
            if (fnmatch($pattern, $class, FNM_NOESCAPE)) {
                return TRUE;
            }
        }

        return FALSE;
    }

}
