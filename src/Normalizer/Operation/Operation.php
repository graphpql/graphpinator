<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Operation;

use Graphpinator\Normalizer\Directive\DirectiveSet;
use Graphpinator\Normalizer\Selection\SelectionSet;
use Graphpinator\Normalizer\Variable\VariableSet;
use Graphpinator\Parser\OperationType;
use Graphpinator\Typesystem\Type;

final readonly class Operation
{
    public function __construct(
        public OperationType $type,
        public ?string $name,
        public Type $rootObject,
        public SelectionSet $children,
        public VariableSet $variables,
        public DirectiveSet $directives,
    )
    {
    }
}
