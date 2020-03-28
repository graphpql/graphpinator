<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Type\Utils;

interface FieldContainer
{
    public function getFields() : \Infinityloop\Graphpinator\Field\FieldSet;

    public function resolveFields(?\Infinityloop\Graphpinator\Parser\RequestFieldSet $requestedFields, \Infinityloop\Graphpinator\Field\ResolveResult $parent) : array;
}
