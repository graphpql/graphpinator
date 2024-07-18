<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Utils;

use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\Field\ResolvableField;
use Graphpinator\Typesystem\Field\ResolvableFieldSet;

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
            new ResolvableField(
                '__typename',
                Container::String()->notNull(),
                function() : string {
                    return $this->getName();
                },
            ),
        ]);
    }
}
