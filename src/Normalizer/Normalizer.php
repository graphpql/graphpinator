<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

final class Normalizer
{
    use \Nette\SmartObject;

    private \Graphpinator\DI\TypeResolver $typeResolver;

    public function __construct(\Graphpinator\DI\TypeResolver $typeResolver)
    {
        $this->typeResolver = $typeResolver;
    }

    public function normalize(
        \Graphpinator\Parser\ParseResult $result,
        \Infinityloop\Utils\Json $variables
    ) : \Graphpinator\Request\Operation
    {

    }

    private function checkVariables() {

    }

    private function expandFragments()
    {

    }

    private function insertVariableValues()
    {

    }

    private function insertTypeObjects()
    {

    }
}
