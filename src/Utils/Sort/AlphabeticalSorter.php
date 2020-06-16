<?php

declare(strict_types = 1);

namespace Graphpinator\Utils\Sort;

final class AlphabeticalSorter implements PrintSorter
{
    public function sortTypes(array $types) : array
    {
        \ksort($types);

        return $types;
    }

    public function sortDirectives(array $directives) : array
    {
        \ksort($directives);

        return $directives;
    }
}
