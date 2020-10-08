<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Directive;

final class DirectiveSet extends \Infinityloop\Utils\ObjectSet
{
    protected const INNER_CLASS = Directive::class;

    private string $location;

    public function __construct(array $data, string $location)
    {
        parent::__construct($data);

        $this->location = $location;
    }

    public function current() : Directive
    {
        return parent::current();
    }

    public function offsetGet($offset) : Directive
    {
        return parent::offsetGet($offset);
    }

    public function getLocation() : string
    {
        return $this->location;
    }

    public function normalize(\Graphpinator\Container\Container $typeContainer) : \Graphpinator\Normalizer\Directive\DirectiveSet
    {
        $normalized = [];

        foreach ($this as $variable) {
            $normalized[] = $variable->normalize($typeContainer);
        }

        return new \Graphpinator\Normalizer\Directive\DirectiveSet($normalized, $this->location);
    }
}
