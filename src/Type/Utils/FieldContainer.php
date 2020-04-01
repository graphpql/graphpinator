<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Utils;

interface FieldContainer
{
    public function getFields() : \Graphpinator\Field\FieldSet;

    public function resolveFields(?\Graphpinator\Request\FieldSet $requestedFields, \Graphpinator\Field\ResolveResult $parent) : array;
}
