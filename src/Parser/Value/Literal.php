<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Value;

final class Literal implements \Graphpinator\Parser\Value\Value
{
    use \Nette\SmartObject;

    public function __construct(
        private string|int|float|bool|null|array|\stdClass $value
    ) {}

    public function getRawValue() : string|int|float|bool|null|array|\stdClass
    {
        return $this->value;
    }
}
