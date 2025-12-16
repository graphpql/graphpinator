<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Variable;

use Graphpinator\Normalizer\Directive\DirectiveSet;
use Graphpinator\Normalizer\Exception\VariableTypeInputable;
use Graphpinator\Typesystem\Contract\Type;
use Graphpinator\Typesystem\Visitor\IsInputableVisitor;
use Graphpinator\Value\InputedValue;

final class Variable
{
    private DirectiveSet $directives;

    public function __construct(
        private string $name,
        private Type $type,
        private ?InputedValue $defaultValue,
    )
    {
        if (!$type->accept(new IsInputableVisitor())) {
            throw new VariableTypeInputable($this->name);
        }

        $this->directives = new DirectiveSet();
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getType() : Type
    {
        return $this->type;
    }

    public function getDefaultValue() : ?InputedValue
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
