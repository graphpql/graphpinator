<?php

declare(strict_types = 1);

namespace Graphpinator\Utils\Sort;

interface PrintSorter
{
    public function sortTypes(array $types) : array;

    public function sortDirectives(array $directives) : array;
}
