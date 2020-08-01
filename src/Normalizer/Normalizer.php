<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

final class Normalizer
{
    use \Nette\SmartObject;

    private \Graphpinator\Type\Schema $schema;

    public function __construct(\Graphpinator\Type\Schema $schema)
    {
        $this->schema = $schema;
    }

    public function normalize(\Graphpinator\Parser\ParseResult $parseResult) : OperationSet
    {
        return $parseResult->normalize($this->schema);
    }
}
