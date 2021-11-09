<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Variable;

use \Graphpinator\Normalizer\Directive\DirectiveSet;

final class Variable
{
    use \Nette\SmartObject;

    private DirectiveSet $directives;

    public function __construct(
        private string $name,
        private \Graphpinator\Typesystem\Contract\Inputable $type,
        private ?\Graphpinator\Value\InputedValue $defaultValue,
    )
    {
        $this->directives = new DirectiveSet();
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getType() : \Graphpinator\Typesystem\Contract\Inputable
    {
        return $this->type;
    }

    public function getDefaultValue() : ?\Graphpinator\Value\InputedValue
    {
        return $this->defaultValue;
    }

    public function setDirectives(DirectiveSet $directives) : void
    {
        $this->directives = $directives;
    }

    public function getDirectives() : DirectiveSet
    {
        return $this->directives;
    }
}
