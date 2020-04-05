<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\FragmentSpread;

final class NamedFragmentSpread implements FragmentSpread
{
    use \Nette\SmartObject;

    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getFields(\Graphpinator\Parser\Fragment\FragmentSet $fragmentDefinitions) : \Graphpinator\Parser\FieldSet
    {
        if ($fragmentDefinitions->offsetExists($this->name)) {
            return $fragmentDefinitions->offsetGet($this->name)->getFields();
        }

        throw new \Exception('Unknown fragment');
    }
}
