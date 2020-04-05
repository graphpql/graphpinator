<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\FragmentSpread;

interface FragmentSpread
{
    public function getFields(\Graphpinator\Parser\Fragment\FragmentSet $fragmentDefinitions) : \Graphpinator\Parser\FieldSet;
}
