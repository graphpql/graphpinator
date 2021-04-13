<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

interface EntityVisitor extends \Graphpinator\Typesystem\NamedTypeVisitor
{
    public function visitSchema(\Graphpinator\Type\Schema $schema) : mixed;

    public function visitDirective(\Graphpinator\Directive\Directive $directive) : mixed;
}
