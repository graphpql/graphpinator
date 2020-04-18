<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Directive;

final class DirectiveSet extends \Infinityloop\Utils\ImmutableSet
{
    private string $location;

    public function __construct(array $data, string $location)
    {
        $validatedData = [];
        $directiveTypes = [];

        foreach ($data as $object) {
            if (!$object instanceof Directive) {
                throw new \Exception('Invalid input.');
            }

            if (!\in_array($location, $object->getDirective()->getLocations(), true)) {
                throw new \Exception('Invalid location');
            }

            $validatedData[] = $object;

            if ($object->getDirective()->isRepeatable()) {
                continue;
            }

            if (\array_key_exists($object->getDirective()->getName(), $directiveTypes)) {
                throw new \Exception('Duplicated directive usage.');
            }

            $directiveTypes[$object->getDirective()->getName()] = true;
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

    public function applyVariables(\Graphpinator\Resolver\VariableValueSet $variables) : self
    {
        $fields = [];

        foreach ($this as $directive) {
            $fields[] = $directive->applyVariables($variables);
        }

        return new self($fields, $this->location);
    }
}
