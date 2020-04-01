<?php

declare(strict_types = 1);

namespace Graphpinator\Parser;

final class NamedFragmentSpread implements FragmentSpread
{
    use \Nette\SmartObject;

    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
