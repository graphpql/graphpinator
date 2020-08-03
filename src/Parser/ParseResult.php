<?php

declare(strict_types = 1);

namespace Graphpinator\Parser;

final class ParseResult
{
    use \Nette\SmartObject;

    private \Graphpinator\Parser\Operation\OperationSet $operations;
    private \Graphpinator\Parser\Fragment\FragmentSet $fragments;

    public function __construct(
        \Graphpinator\Parser\Operation\OperationSet $operations,
        \Graphpinator\Parser\Fragment\FragmentSet $fragments
    )
    {
        $this->operations = $operations;
        $this->fragments = $fragments;
    }

    public function getOperations() : \Graphpinator\Parser\Operation\OperationSet
    {
        return $this->operations;
    }

    public function getFragments() : \Graphpinator\Parser\Fragment\FragmentSet
    {
        return $this->fragments;
    }

    public function normalize(\Graphpinator\Type\Schema $schema) : \Graphpinator\Normalizer\Operation\OperationSet
    {
        foreach ($this->fragments as $fragment) {
            $fragment->validateCycles($this->fragments, []);
        }

        return $this->operations->normalize($schema, $this->fragments);
    }
}
