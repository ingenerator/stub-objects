<?php

namespace test\unit\CasterGuesser;

use Ingenerator\StubObjects\CasterGuesser\CasterGuesser;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

abstract class BaseCasterGuesserTestCase extends TestCase
{

    protected function assertGuesses(array $expect_providers, string $class, CasterGuesser $guesser)
    {
        $reflection = new ReflectionClass($class);

        $actual_guesses = [];
        foreach (array_keys($expect_providers) as $prop_name) {
            $provider = $guesser->guessCaster($reflection->getProperty($prop_name));
            $actual_guesses[$prop_name] = match ($provider) {
                FALSE => FALSE,
                default => $provider::class,
            };
        }

        $this->assertSame($expect_providers, $actual_guesses);
    }
}
