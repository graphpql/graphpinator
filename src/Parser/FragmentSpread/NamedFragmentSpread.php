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

    public function spread(\Graphpinator\Parser\FieldSet $target, array $fragmentDefinitions) : void
    {
        if (!\array_key_exists($this->name, $fragmentDefinitions)) {
            throw new \Exception('Unknwon fragment');
        }


    }
}
