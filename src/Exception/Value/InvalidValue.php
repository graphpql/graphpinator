<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Value;

final class InvalidValue extends \Graphpinator\Exception\Value\ValueError
{
    public const MESSAGE = 'Invalid value resolved for type "%s" - got %s.';

    public function __construct(string $type, $rawValue)
    {
        $this->messageArgs = [$type, $this->printValue($rawValue)];

        parent::__construct();
    }

    private function printValue($rawValue) : string
    {
        if (\is_scalar($rawValue)) {
            return '"' . (string) $rawValue . '"';
        }

        if (\is_array($rawValue)) {
            return 'list';
        }

        if ($rawValue instanceof \stdClass) {
            return 'object';
        }

        throw new \RuntimeException();
    }
}
