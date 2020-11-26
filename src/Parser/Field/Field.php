<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Field;

final class Field
{
    use \Nette\SmartObject;

    public function __construct(
        private string $name,
        private ?string $alias = null,
        private ?\Graphpinator\Parser\Field\FieldSet $children = null,
        private ?\Graphpinator\Parser\Value\ArgumentValueSet $arguments = null,
        private ?\Graphpinator\Parser\Directive\DirectiveSet $directives = null,
    ) {}

    public function getName() : string
    {
        return $this->name;
    }

    public function getAlias() : ?string
    {
        return $this->alias;
    }

    public function getFields() : ?\Graphpinator\Parser\Field\FieldSet
    {
        return $this->children;
    }

    public function getArguments() : ?\Graphpinator\Parser\Value\ArgumentValueSet
    {
        return $this->arguments;
    }

    public function getDirectives() : ?\Graphpinator\Parser\Directive\DirectiveSet
    {
        return $this->directives;
    }

    public function normalize(
        \Graphpinator\Type\Contract\NamedDefinition $parentType,
        \Graphpinator\Container\Container $typeContainer,
        \Graphpinator\Parser\Fragment\FragmentSet $fragmentDefinitions,
    ) : \Graphpinator\Normalizer\Field\Field
    {
        return new \Graphpinator\Normalizer\Field\Field(
            $this,
            $parentType,
            $typeContainer,
            $fragmentDefinitions,
        );
    }
}
