<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

/**
 * Interface Entity
 *
 * Entity is a first class citizen in typesystem, currently Schema, Type definition and Directive definition.
 */
interface Entity extends \Graphpinator\Typesystem\Contract\Component
{
    public function accept(\Graphpinator\Typesystem\Contract\EntityVisitor $visitor) : mixed;
}
