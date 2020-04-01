<?php

declare(strict_types = 1);

namespace Graphpinator\Parser;

final class Fragment
{
    use \Nette\SmartObject;

    public function __construct(string $name, \Graphpinator\Parser\TypeRef\NamedTypeRef $typeCond, \Graphpinator\Parser\FieldSet $fields)
    {
    }
}
