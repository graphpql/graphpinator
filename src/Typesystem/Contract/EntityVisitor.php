<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

use Graphpinator\Typesystem\Directive;
use Graphpinator\Typesystem\Schema;

interface EntityVisitor extends NamedTypeVisitor
{
    public function visitSchema(Schema $schema) : mixed;

    public function visitDirective(Directive $directive) : mixed;
}
