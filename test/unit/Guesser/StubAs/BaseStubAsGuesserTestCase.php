<?php

namespace test\unit\Guesser\StubAs;

use Ingenerator\StubObjects\Guesser\StubAsGuesser;
use Ingenerator\StubObjects\StubbingContext;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use test\TestUtils;

abstract class BaseStubAsGuesserTestCase extends TestCase
{

    protected function assertGuesses(
        array $expect_providers,
        string $class,
        StubAsGuesser $guesser,
        ?StubbingContext $context = NULL
    ) {
        $context ??= TestUtils::fakeStubbingContext();
        $reflection = new ReflectionClass($class);

        $actual_guesses = [];
        foreach (array_keys($expect_providers) as $prop_name) {
            $provider = $guesser->guessCaster($reflection->getProperty($prop_name), $context);
            $actual_guesses[$prop_name] = match ($provider) {
                FALSE => FALSE,
                default => $provider::class,
            };
        }

        $this->assertSame($expect_providers, $actual_guesses);
    }
}
