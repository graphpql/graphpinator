<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Validator\Module;

interface ValidatorModule
{
    public function validate() : void;
}
