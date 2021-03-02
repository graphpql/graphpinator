<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Value;

final class EnumLiteral implements \Graphpinator\Parser\Value\Value
{
    use \Nette\SmartObject;

    public function __construct(
        private string $value
    ) {}

    public function getRawValue() : string
    {
        return $this->value;
    }

    public function accept(ValueVisitor $valueVisitor) : mixed
    {
        return $valueVisitor->visitEnumLiteral($this);
    }
}
