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

    public function runQuery(string $request, \Infinityloop\Utils\Json $variables) : \Graphpinator\Resolver\OperationResult
    {
        return \Graphpinator\Parser\Parser::parseString($request)->normalize($this->resolver)->execute($variables);
    }
}
