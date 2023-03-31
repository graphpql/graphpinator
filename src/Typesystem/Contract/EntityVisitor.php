<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

interface EntityVisitor extends NamedTypeVisitor
{
    public function visitSchema(\Graphpinator\Typesystem\Schema $schema) : mixed;

    public function visitDirective(\Graphpinator\Typesystem\Directive $directive) : mixed;
}
