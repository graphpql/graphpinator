<?php

declare(strict_types = 1);

namespace Graphpinator\Printable;

trait TRepeatablePrint
{
    private function printItems(PrintableSet $set, int $indentLevel = 1) : string
    {
        $result = '';
        $previousHasDescription = false;
        $isFirst = true;

        foreach ($set as $item) {
            $currentHasDescription = $item->hasDescription();

            if (!$isFirst && ($previousHasDescription || $currentHasDescription)) {
                $result .= \PHP_EOL;
            }

            $result .= $item->printSchema($indentLevel) . \PHP_EOL;
            $previousHasDescription = $currentHasDescription;
            $isFirst = false;
        }

        return $result;
    }
}
