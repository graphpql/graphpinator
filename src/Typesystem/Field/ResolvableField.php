<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Field;

final class ResolvableField extends Field
{
    private \Closure $resolveFn;

    public function __construct(string $name, \Graphpinator\Typesystem\Contract\Outputable $type, callable $resolveFn)
    {
        parent::__construct($name, $type);
        $this->resolveFn = $resolveFn;
    }

    public static function create(string $name, \Graphpinator\Typesystem\Contract\Outputable $type, ?callable $resolveFn = null) : self
    {
        return new self($name, $type, $resolveFn);
    }

    public function getResolveFunction() : \Closure
    {
        return $this->resolveFn;
    }
}
