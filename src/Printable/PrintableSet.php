<?php

declare(strict_types = 1);

namespace Graphpinator\Printable;

interface PrintableSet
{
    public function current() : Printable;

    public function key();

    public function next() : void;

    public function rewind() : void;

    public function valid() : bool;
}
