<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

use Graphpinator\Resolver\VariableValueSet;

final class Field
{
    use \Nette\SmartObject;

    private string $name;
    private string $alias;
    private \Graphpinator\Parser\Value\NamedValueSet $arguments;
    private ?\Graphpinator\Normalizer\FieldSet $children;
    private ?\Graphpinator\Type\Contract\NamedDefinition $typeCond;

    public function __construct(
        string $name,
        ?string $alias = null,
        ?\Graphpinator\Parser\Value\NamedValueSet $arguments = null,
        ?\Graphpinator\Normalizer\FieldSet $children = null,
        ?\Graphpinator\Type\Contract\NamedDefinition $typeCond = null
    )
    {
        $this->name = $name;
        $this->alias = $alias ?? $name;
        $this->arguments = $arguments ?? new \Graphpinator\Parser\Value\NamedValueSet([]);
        $this->children = $children;
        $this->typeCond = $typeCond;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getAlias() : string
    {
        return $this->alias;
    }

    public function getArguments() : \Graphpinator\Parser\Value\NamedValueSet
    {
        return $this->arguments;
    }

    public function getFields() : ?\Graphpinator\Normalizer\FieldSet
    {
        return $this->children;
    }

    public function getTypeCondition() : ?\Graphpinator\Type\Contract\NamedDefinition
    {
        return $this->typeCond;
    }

    public function typeMatches(\Graphpinator\Type\Contract\Definition $type) : bool
    {
        if ($this->typeCond instanceof \Graphpinator\Type\Contract\NamedDefinition) {
            return $type->isInstanceOf($this->typeCond);
        }

        return true;
    }

    public function applyVariables(VariableValueSet $variables) : self
    {
        return new self(
            $this->name,
            $this->alias,
            $this->arguments->applyVariables($variables),
            $this->children instanceof FieldSet
                ? $this->children->applyVariables($variables)
                : null,
            $this->typeCond,
        );
    }
}
