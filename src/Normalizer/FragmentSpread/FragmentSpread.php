<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\FragmentSpread;

final class FragmentSpread
{
    use \Nette\SmartObject;

    public function __construct(
        private \Graphpinator\Normalizer\Field\FieldSet $fields,
    )
    {
    }

    public function getFields() : \Graphpinator\Normalizer\Field\FieldSet
    {
        return $this->fields;
    }
}
