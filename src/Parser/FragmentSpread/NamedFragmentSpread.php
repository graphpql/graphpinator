<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\FragmentSpread;

final class NamedFragmentSpread implements \Graphpinator\Parser\FragmentSpread\FragmentSpread
{
    use \Nette\SmartObject;

    private string $name;
    private \Graphpinator\Parser\Directive\DirectiveSet $directives;

    public function __construct(
        string $name,
        ?\Graphpinator\Parser\Directive\DirectiveSet $directives = null,
    )
    {
        $this->name = $name;
        $this->directives = $directives
            ?? new \Graphpinator\Parser\Directive\DirectiveSet();
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getDirectives() : \Graphpinator\Parser\Directive\DirectiveSet
    {
        return $this->directives;
    }
}
