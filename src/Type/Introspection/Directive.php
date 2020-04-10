<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Introspection;

final class Directive extends \Graphpinator\Type\Type
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getFieldDefinition(): \Graphpinator\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Field\ResolvableFieldSet([
        ]);
    }
}
