<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

use Graphpinator\Typesystem\Argument\ArgumentSet;
use Graphpinator\Typesystem\Location\ExecutableDirectiveLocation;
use Graphpinator\Typesystem\Location\TypeSystemDirectiveLocation;

interface Directive extends Entity
{
    public function getName() : string;

    public function getDescription() : ?string;

    public function isRepeatable() : bool;

    /**
     * @return list<(ExecutableDirectiveLocation|TypeSystemDirectiveLocation)>
     */
    public function getLocations() : array;

    public function getArguments() : ArgumentSet;
}
