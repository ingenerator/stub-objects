<?php

namespace test\unit\Guesser\StubDefault;

use Ingenerator\StubObjects\Guesser\StubDefaultGuesser;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

abstract class BaseStubDefaultGuesserTestCase extends TestCase
{

    protected function assertGuesses(array $expect_providers, string $class, StubDefaultGuesser $guesser)
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
