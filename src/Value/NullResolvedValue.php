<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class NullResolvedValue implements \Graphpinator\Value\OutputValue, \Graphpinator\Value\NullValue
{
    use \Nette\SmartObject;

    private \Graphpinator\Type\Contract\Resolvable $type;

    public function __construct(\Graphpinator\Type\Contract\Resolvable $type)
    {
        $this->type = $type;
    }

    public function getRawValue(bool $forResolvers = false) : ?bool
    {
        return null;
    }

    public function getType() : \Graphpinator\Type\Contract\Resolvable
    {
        return $this->type;
    }

    public function jsonSerialize() : ?bool
    {
        return null;
    }
}
