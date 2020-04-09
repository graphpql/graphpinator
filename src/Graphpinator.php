<?php

declare(strict_types=1);

namespace Graphpinator;

final class Graphpinator
{
    use \Nette\SmartObject;

    private \Graphpinator\Type\Schema $schema;

    public function __construct(\Graphpinator\Type\Schema $schema)
    {
        $this->schema = $schema;
    }

    public function runQuery(string $request, \Infinityloop\Utils\Json $variables) : \Graphpinator\Resolver\OperationResult
    {
        return \Graphpinator\Parser\Parser::parseString($request)->normalize($this->schema)->execute($variables);
    }
}
