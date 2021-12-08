<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Exception;

interface VarianceError
{
    public function getExplanationMessage() : string;
}
