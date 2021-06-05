<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Variable;

final class Variable
{
    use \Nette\SmartObject;

    private \Graphpinator\Normalizer\Directive\DirectiveSet $directives;

    public function __construct(
        private string $name,
        private \Graphpinator\Typesystem\Contract\Inputable $type,
        private ?\Graphpinator\Value\InputedValue $defaultValue,
    )
    {
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

    public function setDirectives(\Graphpinator\Normalizer\Directive\DirectiveSet $directives) : void
    {
        $this->directives = $directives;
    }

    public function getDirectives() : \Graphpinator\Normalizer\Directive\DirectiveSet
    {
        return $this->directives;
    }
}
