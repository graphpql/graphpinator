<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

use Graphpinator\Typesystem\Directive;
use Graphpinator\Typesystem\Schema;

/**
 * @template T
 * @template-extends NamedTypeVisitor<T>
 */
interface EntityVisitor extends NamedTypeVisitor
{
    /**
     * @return T
     */
    public function visitSchema(Schema $schema) : mixed;

    /**
     * @return T
     */
    public function visitDirective(Directive $directive) : mixed;
}
