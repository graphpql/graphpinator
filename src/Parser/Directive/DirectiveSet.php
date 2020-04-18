<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Directive;

final class DirectiveSet extends \Infinityloop\Utils\ImmutableSet
{
    private string $location;

    public function __construct(array $data, string $location)
    {
        $validatedData = [];

        foreach ($data as $object) {
            if (!$object instanceof Directive) {
                throw new \Exception('Invalid input.');
            }

            $validatedData[] = $object;
        }

        parent::__construct($validatedData);
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

    public function normalize(\Graphpinator\Type\Container\Container $typeContainer) : \Graphpinator\Normalizer\Directive\DirectiveSet
    {
        $normalized = [];

        foreach ($this as $variable) {
            $normalized[] = $variable->normalize($typeContainer);
        }

        return new \Graphpinator\Normalizer\Directive\DirectiveSet($normalized, $this->location);
    }
}
