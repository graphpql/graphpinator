<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Field;

use Graphpinator\Typesystem\Contract\Type;

class ResolvableField extends Field
{
    private \Closure $resolveFn;

    public function __construct(
        string $name,
        Type $type,
        callable $resolveFn,
    )
    {
        parent::__construct($name, $type);
        $this->resolveFn = $resolveFn;
    }

    #[\Override]
    public static function create(string $name, Type $type, ?callable $resolveFn = null) : self
    {
        return new self($name, $type, $resolveFn);
    }

    public function getResolveFunction() : \Closure
    {
        return $this->resolveFn;
    }
}
