<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Type\Utils;

interface FieldContainer
{
    public function getFields() : \Infinityloop\Graphpinator\Field\FieldSet;

    public function resolveFields(?\Infinityloop\Graphpinator\Request\FieldSet $requestedFields, \Infinityloop\Graphpinator\Field\ResolveResult $parent) : array;
}
