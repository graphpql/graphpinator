<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Variable;

final class Variable
{
    use \Nette\SmartObject;

    public function __construct(
        private string $name,
        private \Graphpinator\Type\Contract\Inputable $type,
        private ?\Graphpinator\Value\InputedValue $defaultValue,
        private \Graphpinator\Normalizer\Directive\DirectiveSet $directives,
    )
    {
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getType() : \Graphpinator\Type\Contract\Inputable
    {
        return $this->type;
    }

    public function getDefaultValue() : ?\Graphpinator\Value\InputedValue
    {
        return $this->defaultValue;
    }

    public function getDirectives() : \Graphpinator\Normalizer\Directive\DirectiveSet
    {
        return $this->directives;
    }
}
