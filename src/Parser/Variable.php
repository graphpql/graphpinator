<?php

declare(strict_types = 1);

namespace Graphpinator\Parser;

final class Variable
{
    use \Nette\SmartObject;

    public function __construct(string $name, \Graphpinator\Parser\TypeRef\TypeRef $typeCond, ?\Graphpinator\Parser\Value\Value $default = null)
    {
    }
}
