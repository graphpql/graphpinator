<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Value;

final class ObjectVal implements Value
{
    use \Nette\SmartObject;

    private array $value;

    public function __construct(array $value)
    {
        $this->value = $value;
    }
}
