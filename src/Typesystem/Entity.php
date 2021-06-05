<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

/**
 * Interface Entity
 *
 * Entity is a first class citizen in typesystem, currently Schema, Type definition and Directive definition.
 */
interface Entity extends \Graphpinator\Typesystem\Component
{
    public function accept(\Graphpinator\Typesystem\EntityVisitor $visitor) : mixed;
}
