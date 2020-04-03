<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\FragmentSpread;

final class TypeFragmentSpread implements FragmentSpread
{
    use \Nette\SmartObject;

    private string $type;
    private \Graphpinator\Parser\FieldSet $fields;

    public function __construct(string $type, \Graphpinator\Parser\FieldSet $fields)
    {
        $this->type = $type;
        $this->fields = $fields;
    }

    public function spread(\Graphpinator\Parser\FieldSet $target, array $fragmentDefinitions): void
    {
        // TODO: Implement spread() method.
    }
}
