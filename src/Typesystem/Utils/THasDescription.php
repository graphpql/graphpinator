<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Utils;

trait THasDescription
{
    final public function getDescription() : ?string
    {
        $ref = new \ReflectionClass($this);
        $attrs = $ref->getAttributes(\Graphpinator\Typesystem\Attribute\Description::class);

        if (\count($attrs) === 1) {
            $attr = $attrs[0]->newInstance();
            \assert($attr instanceof \Graphpinator\Typesystem\Attribute\Description);

            return $attr->getValue();
        }

        return $ref->getConstant('DESCRIPTION') ?: null;
    }
}
