<?php

declare(strict_types=1);

namespace Graphpinator;

final class Graphpinator
{
    use \Nette\StaticClass;

    private \Graphpinator\Type\Resolver $resolver;

    public function __construct(\Graphpinator\Type\Resolver $resolver)
    {
        $this->resolver = $resolver;
    }

    public function runQuery(string $request, \Infinityloop\Utils\Json $variables) : \Graphpinator\Request\ExecutionResult
    {
        return \Graphpinator\Parser\Parser::parseRequest($request)->normalize($this->resolver)->execute($variables);
    }
}
