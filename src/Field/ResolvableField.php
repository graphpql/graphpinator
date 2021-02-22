<?php

declare(strict_types = 1);

namespace Graphpinator\Field;

final class ResolvableField extends \Graphpinator\Field\Field
{
    private \Closure $resolveFn;

    public function __construct(string $name, \Graphpinator\Type\Contract\Outputable $type, callable $resolveFn)
    {
        parent::__construct($name, $type);
        $this->resolveFn = $resolveFn;
    }

    public static function create(string $name, \Graphpinator\Type\Contract\Outputable $type, ?callable $resolveFn = null) : self
    {
        return new self($name, $type, $resolveFn);
    }

    public function getResolveFunction() : \Closure
    {
        return $this->resolveFn;
    }
}
