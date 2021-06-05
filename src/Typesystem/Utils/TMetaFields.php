<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Utils;

trait TMetaFields
{
    protected ?\Graphpinator\Typesystem\Field\ResolvableFieldSet $metaFields = null;

    public function getMetaFields() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
    {
        if (!$this->metaFields instanceof \Graphpinator\Typesystem\Field\ResolvableFieldSet) {
            $this->metaFields = $this->getMetaFieldDefinition();
        }

        return $this->metaFields;
    }

    private function getMetaFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
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
