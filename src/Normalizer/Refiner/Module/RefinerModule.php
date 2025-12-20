<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Refiner\Module;

interface RefinerModule
{
    public function refine() : void;
}
