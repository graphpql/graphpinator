<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Value;

final class InvalidValue extends \Graphpinator\Exception\Value\ValueError
{
    public const MESSAGE = 'Invalid value resolved for type "%s" - got %s.';

    public function __construct(string $type, $rawValue, bool $outputable)
    {
        $this->messageArgs = [$type, $this->printValue($rawValue)];

        parent::__construct($outputable);
    }

    private function printValue($rawValue) : string
    {
        if ($rawValue === null) {
            return 'null';
        }

        if (\is_string($rawValue)) {
            return '"' . $rawValue . '"';
        }

        if (\is_scalar($rawValue)) {
            return (string) $rawValue;
        }

        if (\is_array($rawValue)) {
            return 'list';
        }

        if ($rawValue instanceof \stdClass) {
            return 'object';
        }

        return \get_class($rawValue);
    }
}
