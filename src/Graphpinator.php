<?php

declare(strict_types=1);

namespace Graphpinator;

final class Graphpinator
{
    use \Nette\StaticClass;

    private \Graphpinator\DI\TypeResolver $typeResolver;

    public function __construct(\Graphpinator\DI\TypeResolver $typeResolver)
    {
        $this->typeResolver = $typeResolver;
    }

    public function runQuery(string $request, \Infinityloop\Utils\Json $variables) : \Graphpinator\Request\ExecutionResult
    {
        $parser = new \Graphpinator\Parser\Parser($request);

        return $parser->parse()->normalize($this->typeResolver, $variables)->execute();
    }
}
