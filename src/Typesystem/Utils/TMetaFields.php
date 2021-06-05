<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Contract;

trait TMetaFields
{
    protected ?\Graphpinator\Field\ResolvableFieldSet $metaFields = null;

    public function getMetaFields() : \Graphpinator\Field\ResolvableFieldSet
    {
        if (!$this->metaFields instanceof \Graphpinator\Field\ResolvableFieldSet) {
            $this->metaFields = $this->getMetaFieldDefinition();
        }

        return $this->metaFields;
    }

    private function getMetaFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Field\ResolvableFieldSet([
            new \Graphpinator\Field\ResolvableField(
                '__typename',
                \Graphpinator\Container\Container::String()->notNull(),
                function() : string {
                    return $this->getName();
                },
            ),
        ]);
    }
}
