<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Directive;

final class DirectiveSet extends \Infinityloop\Utils\ObjectSet
{
    protected const INNER_CLASS = Directive::class;

    private string $location;

    public function __construct(array $data, string $location)
    {
        parent::__construct($data);

        $this->location = $location;
        $directiveTypes = [];

        foreach ($this->array as $object) {
            $directive = $object->getDirective();

            if (!\in_array($location, $directive->getLocations(), true)) {
                throw new \Graphpinator\Exception\Normalizer\MisplacedDirective();
            }

            if ($directive->isRepeatable()) {
                continue;
            }

            if (\array_key_exists($directive->getName(), $directiveTypes)) {
                throw new \Graphpinator\Exception\Normalizer\DuplicatedDirective();
            }

            $directiveTypes[$directive->getName()] = true;
        }
    }

    public function current() : Directive
    {
        return parent::current();
    }

    public function offsetGet($offset) : Directive
    {
        if (!$this->offsetExists($offset)) {
            throw new \Graphpinator\Exception\Normalizer\DirectiveNotDefined();
        }

        return $this->array[$offset];
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
