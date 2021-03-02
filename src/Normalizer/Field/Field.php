<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Field;

final class Field
{
    use \Nette\SmartObject;

    public function __construct(
        private \Graphpinator\Field\Field $field,
        private string $alias,
        private \Graphpinator\Value\ArgumentValueSet $arguments,
        private \Graphpinator\Normalizer\Directive\DirectiveSet $directives,
        private ?\Graphpinator\Normalizer\Field\FieldSet $children = null,
        private ?\Graphpinator\Type\Contract\TypeConditionable $typeCond = null,
    ) {}

    public function getField() : \Graphpinator\Field\Field
    {
        return $this->field;
    }

    public function getName() : string
    {
        return $this->field->getName();
    }

    public function getAlias() : string
    {
        return $this->alias;
    }

    public function getArguments() : \Graphpinator\Value\ArgumentValueSet
    {
        return $this->arguments;
    }

    public function getDirectives() : \Graphpinator\Normalizer\Directive\DirectiveSet
    {
        return $this->directives;
    }

    public function getFields() : ?\Graphpinator\Normalizer\Field\FieldSet
    {
        return $this->children;
    }

    public function getTypeCondition() : ?\Graphpinator\Type\Contract\TypeConditionable
    {
        return $this->typeCond;
    }

    public function applyFragmentTypeCondition(?\Graphpinator\Type\Contract\TypeConditionable $typeCond) : void
    {
        if (!$typeCond instanceof \Graphpinator\Type\Contract\TypeConditionable) {
            return;
        }

        if (!$this->typeCond instanceof \Graphpinator\Type\Contract\TypeConditionable) {
            $this->typeCond = $typeCond;

            return;
        }

        if ($this->typeCond->isInstanceOf($typeCond)) {
            return;
        }

        throw new \Graphpinator\Exception\Normalizer\InvalidFragmentType($this->typeCond->getName(), $typeCond->getName());
    }

    public function applyVariables(\Graphpinator\Normalizer\VariableValueSet $variables) : void
    {
        $this->arguments->applyVariables($variables);
        $this->directives->applyVariables($variables);
        $this->children?->applyVariables($variables);
    }
}
