<?php

declare(strict_types = 1);

namespace Graphpinator\Parser;

final class ParseResult
{
    use \Nette\SmartObject;

    private \Graphpinator\Parser\Operation $operation;
    private \Graphpinator\Parser\Fragment\FragmentSet $fragments;

    public function __construct(\Graphpinator\Parser\Operation $operation, \Graphpinator\Parser\Fragment\FragmentSet $fragments)
    {
        $this->operation = $operation;
        $this->fragments = $fragments;
    }

    public function getOperation() : \Graphpinator\Parser\Operation
    {
        return $this->operation;
    }

    public function getFragments() : \Graphpinator\Parser\Fragment\FragmentSet
    {
        return $this->fragments;
    }

    public function normalize(
        \Graphpinator\DI\TypeResolver $typeResolver,
        \Infinityloop\Utils\Json $variables
    ) : \Graphpinator\Request\Operation
    {
        return $this->operation->normalize($typeResolver, $this->fragments, $variables);
    }
}
