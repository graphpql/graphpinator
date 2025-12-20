<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Variable;

use Graphpinator\Normalizer\Directive\DirectiveSet;
use Graphpinator\Normalizer\Exception\VariableTypeInputable;
use Graphpinator\Typesystem\Contract\Type;
use Graphpinator\Typesystem\Visitor\IsInputableVisitor;
use Graphpinator\Value\InputedValue;

final readonly class Variable
{
    public function __construct(
        public string $name,
        public Type $type,
        public ?InputedValue $defaultValue = null,
        public DirectiveSet $directives = new DirectiveSet(),
    )
    {
        if (!$type->accept(new IsInputableVisitor())) {
            throw new VariableTypeInputable($this->name);
        }
    }
}
