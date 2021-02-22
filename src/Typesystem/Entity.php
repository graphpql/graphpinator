<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

interface Entity extends Component
{
    public function accept(\Graphpinator\Typesystem\EntityVisitor $visitor) : mixed;
}
