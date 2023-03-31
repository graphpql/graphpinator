<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

interface Directive extends Entity
{
    public function getName() : string;

    public function getDescription() : ?string;

    public function isRepeatable() : bool;

    public function getLocations() : array;

    public function getArguments() : \Graphpinator\Typesystem\Argument\ArgumentSet;
}
