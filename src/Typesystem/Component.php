<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

/**
 * Interface Component
 *
 * Any component of typesystem.
 * For example some more important entities like Type or Directive definition,
 * but also less important like field, argument, enum item or directive usage.
 */
interface Component
{
    public function accept(\Graphpinator\Typesystem\ComponentVisitor $visitor) : mixed;
}
