<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Variable;

use Graphpinator\Normalizer\Directive\DirectiveSet;
use Graphpinator\Typesystem\Contract\Type;
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
