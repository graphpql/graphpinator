<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Utils;

use Graphpinator\Typesystem\Attribute\Description;

trait THasDescription
{
    final public function getDescription() : ?string
    {
        $ref = new \ReflectionClass($this);
        $attrs = $ref->getAttributes(Description::class);

        if (\count($attrs) === 1) {
            return $attrs[0]->newInstance()->getValue();
        }

        return null;
    }
}
