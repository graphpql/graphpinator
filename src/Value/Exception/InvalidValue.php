<?php

declare(strict_types = 1);

namespace Graphpinator\Value\Exception;

use Graphpinator\Typesystem\Contract\Type;
use Graphpinator\Typesystem\Visitor\PrintNameVisitor;

final class InvalidValue extends ValueError
{
    public const MESSAGE = 'Invalid value resolved for type "%s" - got %s.';

    public function __construct(
        Type $type,
        mixed $rawValue,
        bool $outputable,
    )
    {
        parent::__construct($outputable, [$type->accept(new PrintNameVisitor()), $this->printValue($rawValue)]);
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
