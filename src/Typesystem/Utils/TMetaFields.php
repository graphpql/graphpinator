<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Utils;

use \Graphpinator\Typesystem\Field\ResolvableFieldSet;

trait TMetaFields
{
    protected ?ResolvableFieldSet $metaFields = null;

    public function getMetaFields() : ResolvableFieldSet
    {
        if (!$this->metaFields instanceof ResolvableFieldSet) {
            $this->metaFields = $this->getMetaFieldDefinition();
        }

        return $this->metaFields;
    }

    private function getMetaFieldDefinition() : ResolvableFieldSet
    {
        return new ResolvableFieldSet([
            new \Graphpinator\Typesystem\Field\ResolvableField(
                '__typename',
                \Graphpinator\Typesystem\Container::String()->notNull(),
                function() : string {
                    return $this->getName();
                },
            ),
        ]);
    }
}
