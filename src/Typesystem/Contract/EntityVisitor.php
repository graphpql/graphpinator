<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

interface EntityVisitor extends \Graphpinator\Typesystem\Contract\NamedTypeVisitor
{
    public function visitSchema(\Graphpinator\Type\Schema $schema) : mixed;

    public function visitDirective(\Graphpinator\Directive\Directive $directive) : mixed;
}
