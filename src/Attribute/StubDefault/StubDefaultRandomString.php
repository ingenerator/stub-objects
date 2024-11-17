<?php

namespace Ingenerator\StubObjects\Attribute\StubDefault;

use Attribute;
use Ingenerator\StubObjects\Attribute\StubDefault;
use Random\Randomizer;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class StubDefaultRandomString implements StubDefault
{
    public const ALPHANUMERIC = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ12345678';

    public function __construct(
        private int $length = 16,
        private string $chars = self::ALPHANUMERIC,
        private Randomizer $randomizer = new Randomizer()
    ) {

    }

    public function getValue(array $specified_values): string
    {
        return $this->randomizer->getBytesFromString($this->chars, $this->length);
    }
}
