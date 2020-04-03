<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\FragmentSpread;

interface FragmentSpread
{
    public function spread(\Graphpinator\Parser\FieldSet $target, array $fragmentDefinitions) : void;
}
