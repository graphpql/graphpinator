<?php

declare(strict_types = 1);

namespace Graphpinator\Utils;

/**
 * Trait TOptionalDescription which manages description for classes which support it.
 */
trait TOptionalDescription
{
    private ?string $description = null;

    public function getDescription() : ?string
    {
        return $this->description;
    }

    public function setDescription(string $description) : self
    {
        $this->description = $description;

        return $this;
    }

    private function printDescription(int $indentLevel = 0) : string
    {
        $indentation = \str_repeat('  ', $indentLevel);

        if ($this->getDescription() === null) {
            return $indentation;
        }

        if (\mb_strpos($this->getDescription(), \PHP_EOL) !== false) {
            $description = \str_replace(\PHP_EOL, \PHP_EOL . $indentation, $this->getDescription());
            return $indentation . '"""' . \PHP_EOL . $indentation . $description . \PHP_EOL . $indentation . '"""' . \PHP_EOL . $indentation;
        }

        return $indentation . '"' . $this->getDescription() . '"' . \PHP_EOL . $indentation;
    }
}