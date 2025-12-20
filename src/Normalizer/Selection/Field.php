<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Selection;

use Graphpinator\Normalizer\Directive\DirectiveSet;
use Graphpinator\Typesystem\Field\Field as TypesystemField;
use Graphpinator\Value\ArgumentValueSet;

final readonly class Field implements Selection
{
    public function __construct(
        public TypesystemField $field,
        public string $outputName,
        public ArgumentValueSet $arguments,
        public DirectiveSet $directives,
        public ?SelectionSet $children = null,
    )
    {
    }

    public function getName() : string
    {
        return $this->field->getName();
    }

    #[\Override]
    public function accept(SelectionVisitor $visitor) : mixed
    {
        return $visitor->visitField($this);
    }
}
