<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Scalar;

final class EnumItem
{
    use \Nette\SmartObject;

    private string $name;
    private ?string $description;
    private bool $isDeprecated;
    private ?string $deprecationReason;

    public function __construct(string $name, ?string $description = null, bool $isDeprecated = false, ?string $deprecationReason = null)
    {
        $this->name = $name;
        $this->description = $description;
        $this->isDeprecated = $isDeprecated;
        $this->deprecationReason = $deprecationReason;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getDescription() : ?string
    {
        return $this->description;
    }

    public function isDeprecated() : bool
    {
        return $this->isDeprecated;
    }

    public function getDeprecationReason() : ?string
    {
        return $this->deprecationReason;
    }
}
