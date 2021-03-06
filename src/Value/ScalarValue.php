<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class ScalarValue extends LeafValue
{
    private mixed $resolverValue = null;

    public function printValue() : string
    {
        return \json_encode($this->rawValue, \JSON_THROW_ON_ERROR |
            \JSON_UNESCAPED_UNICODE |
            \JSON_UNESCAPED_SLASHES |
            \JSON_PRESERVE_ZERO_FRACTION
        );
    }

    public function getRawValue(bool $forResolvers = false) : mixed
    {
        return ($forResolvers && $this->resolverValue !== null)
            ? $this->resolverValue
            : $this->rawValue;
    }

    public function setResolverValue(mixed $value) : void
    {
        $this->resolverValue = $value;
    }
}
