<?php

declare(strict_types = 1);

namespace Graphpinator\Utils;

/**
 * Trait TOptionalDescription which manages deprecated info for classes which support it.
 */
trait TDeprecatable
{
    private bool $deprecated = false;
    private ?string $deprecationReason = null;

    public function isDeprecated() : bool
    {
        return $this->deprecated;
    }

    public function setDeprecated(bool $deprecated) : void
    {
        $this->deprecated = $deprecated;
    }

    public function getDeprecationReason() : ?string
    {
        return $this->deprecationReason;
    }

    public function setDeprecationReason(string $reason) : void
    {
        $this->deprecationReason = $reason;
    }
}
