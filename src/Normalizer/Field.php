<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

final class Field
{
    use \Nette\SmartObject;

    private string $name;
    private string $alias;
    private \Graphpinator\Parser\Value\NamedValueSet $arguments;
    private \Graphpinator\Normalizer\Directive\DirectiveSet $directives;
    private ?\Graphpinator\Normalizer\FieldSet $children;
    private ?\Graphpinator\Type\Contract\NamedDefinition $typeCond;

    public function __construct(
        string $name,
        ?string $alias = null,
        ?\Graphpinator\Parser\Value\NamedValueSet $arguments = null,
        ?\Graphpinator\Normalizer\Directive\DirectiveSet $directives = null,
        ?\Graphpinator\Normalizer\FieldSet $children = null,
        ?\Graphpinator\Type\Contract\NamedDefinition $typeCond = null
    )
    {
        $this->name = $name;
        $this->alias = $alias
            ?? $name;
        $this->arguments = $arguments
            ?? new \Graphpinator\Parser\Value\NamedValueSet([]);
        $this->directives = $directives
            ?? new \Graphpinator\Normalizer\Directive\DirectiveSet([], \Graphpinator\Directive\ExecutableDirectiveLocation::FIELD);
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

    public function getDirectives() : \Graphpinator\Normalizer\Directive\DirectiveSet
    {
        return $this->directives;
    }

    public function getFields() : ?\Graphpinator\Normalizer\FieldSet
    {
        return $this->children;
    }

    public function getTypeCondition() : ?\Graphpinator\Type\Contract\NamedDefinition
    {
        return $this->typeCond;
    }

    public function applyFragmentTypeCondition(?\Graphpinator\Type\Contract\NamedDefinition $typeCond) : void
    {
        if (!$typeCond instanceof \Graphpinator\Type\Contract\NamedDefinition) {
            return;
        }

        if (!$this->typeCond instanceof \Graphpinator\Type\Contract\NamedDefinition) {
            $this->typeCond = $typeCond;

            return;
        }

        if ($this->typeCond->isInstanceOf($typeCond)) {
            return;
        }

        throw new \Graphpinator\Exception\Normalizer\InvalidFragmentType();
    }

    public function applyVariables(\Graphpinator\Resolver\VariableValueSet $variables) : self
    {
        return new self(
            $this->name,
            $this->alias,
            $this->arguments->applyVariables($variables),
            $this->directives->applyVariables($variables),
            $this->children instanceof FieldSet
                ? $this->children->applyVariables($variables)
                : null,
            $this->typeCond,
        );
    }

    public function mergeField(Field $field) : self
    {
        if ($this->getName() !== $field->getName()) {
            throw new \Graphpinator\Exception\Normalizer\ConflictingFieldAlias();
        }

        $fieldArguments = $field->getArguments();

        foreach ($this->getArguments() as $lhs) {
            if (isset($fieldArguments[$lhs->getName()]) &&
                $lhs->getValue()->isSame($fieldArguments[$lhs->getName()]->getValue())) {
                continue;
            }

            throw new \Graphpinator\Exception\Normalizer\ConflictingFieldArguments();
        }

        if ($this->children instanceof FieldSet) {
            $this->children->mergeFieldSet($field->getFields());
        }

        return $this;
    }
}
