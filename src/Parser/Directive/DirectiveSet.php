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
        if (!$this->offsetExists($offset)) {
            throw new \Graphpinator\Exception\Parser\DirectiveNotDefined();
        }

        return $this->array[$offset];
    }

    public function getLocation() : string
    {
        return $this->location;
    }

    public function normalize(\Graphpinator\Type\Container\Container $typeContainer) : \Graphpinator\Normalizer\Directive\DirectiveSet
    {
        $normalized = [];

        foreach ($this as $variable) {
            $normalized[] = $variable->normalize($typeContainer);
        }

        return new \Graphpinator\Normalizer\Directive\DirectiveSet($normalized, $this->location);
    }
}
