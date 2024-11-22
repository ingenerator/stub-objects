<?php

namespace test\unit\Guesser\StubDefault;

use Ingenerator\StubObjects\Guesser\StubDefaultGuesser;
use Ingenerator\StubObjects\StubbingContext;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use test\TestUtils;

abstract class BaseStubDefaultGuesserTestCase extends TestCase
{

    protected function assertGuesses(
        array $expect_providers,
        string $class,
        StubDefaultGuesser $guesser,
        ?StubbingContext $context = NULL
    ) {
        $context ??= TestUtils::fakeStubbingContext();
        $reflection = new ReflectionClass($class);

        $actual_guesses = [];
        foreach (array_keys($expect_providers) as $prop_name) {
            $provider = $guesser->guessProvider($reflection->getProperty($prop_name), $context);
            $actual_guesses[$prop_name] = match ($provider) {
                FALSE => FALSE,
                default => [$provider::class => $provider->getValue([])]
            };
        }

        $this->assertSame($expect_providers, $actual_guesses);
    }
}
