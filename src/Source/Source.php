<?php

declare(strict_types = 1);

namespace Graphpinator\Source;

interface Source extends \Iterator
{
    public function hasChar() : bool;

    public function getChar() : string;

    public function getLocation() : Location;
}
