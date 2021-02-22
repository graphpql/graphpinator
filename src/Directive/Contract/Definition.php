<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Contract;

interface Definition extends \Graphpinator\Typesystem\Entity
{
    public function getName() : string;

    public function getDescription() : ?string;

    public function isRepeatable() : bool;

    public function getLocations() : array;

    public function getArguments() : \Graphpinator\Argument\ArgumentSet;
}
