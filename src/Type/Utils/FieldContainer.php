<?php

declare(strict_types = 1);

namespace PGQL\Type\Utils;

interface FieldContainer
{
    public function getFields() : \PGQL\Field\FieldSet;

    public function resolveFields(?\PGQL\Parser\RequestFieldSet $requestedFields, \PGQL\Field\ResolveResult $parent) : array;
}
