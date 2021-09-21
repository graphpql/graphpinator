<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\ValidatorModule;

interface ValidatorModule
{
    public function validate() : void;
}
