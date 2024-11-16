<?php

namespace test\unit\DefaultValueGuesser;

use Ingenerator\StubObjects\DefaultValueGuesser\DefaultValueProviderGuesser;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

abstract class BaseDefaultValueProviderGuesserTestCase extends TestCase
{

    protected function assertGuesses(array $expect_providers, string $class, DefaultValueProviderGuesser $guesser)
    {
        $reflection = new ReflectionClass($class);

        $actual_guesses = [];
        foreach (array_keys($expect_providers) as $prop_name) {
            $provider = $guesser->guessProvider($reflection->getProperty($prop_name));
            $actual_guesses[$prop_name] = match ($provider) {
                FALSE => FALSE,
                default => [$provider::class => $provider->getValue([])]
            };
        }

        $this->assertSame($expect_providers, $actual_guesses);
    }
}
