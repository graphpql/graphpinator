<?php

declare(strict_types = 1);

namespace Graphpinator\Tokenizer;

final class Token
{
    use \Nette\SmartObject;

    private string $type;
    private ?string $value;
    private \Graphpinator\Source\Location $location;

    public function __construct(string $type, \Graphpinator\Source\Location $location, ?string $value = null)
    {
        $this->type = $type;
        $this->value = $value;
        $this->location = $location;
    }

    public function getType() : string
    {
        return $this->type;
    }

    public function getValue() : ?string
    {
        return $this->value;
    }

    public function getLocation() : \Graphpinator\Source\Location
    {
        return $this->location;
    }
}
