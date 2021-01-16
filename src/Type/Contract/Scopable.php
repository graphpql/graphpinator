<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Contract;

interface Scopable extends \Graphpinator\Type\Contract\Outputable
{
    public function getField(string $name) : \Graphpinator\Field\Field;
}
