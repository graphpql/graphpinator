<?php

declare(strict_types = 1);

namespace Graphpinator\Parser;

final class TypeFragmentSpread implements FragmentSpread
{
    use \Nette\SmartObject;

    private string $type;
    private FieldSet $fields;

    public function __construct(string $type, FieldSet $fields)
    {
        $this->type = $type;
        $this->fields = $fields;
    }
}
