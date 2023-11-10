<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Value;

final class InvalidValue extends \Graphpinator\Exception\Value\ValueError
{
    public const MESSAGE = 'Invalid value resolved for type "%s" - got %s.';

    public function __construct(string $type, mixed $rawValue, bool $outputable)
    {
        parent::__construct($outputable, [$type, $this->printValue($rawValue)]);
    }

    private function printValue(mixed $rawValue) : string
    {
        if ($rawValue === null || \is_scalar($rawValue)) {
            return \json_encode($rawValue, \JSON_THROW_ON_ERROR);
        }

        if (\is_array($rawValue)) {
            return 'list';
        }

        if ($rawValue instanceof \stdClass) {
            return 'object';
        }

        return $rawValue::class;
    }
}
