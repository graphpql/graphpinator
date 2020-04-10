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

    public function setDescription(string $description) : void
    {
        $this->description = $description;
    }
}
