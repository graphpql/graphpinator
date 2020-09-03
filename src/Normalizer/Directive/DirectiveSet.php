<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Directive;

final class DirectiveSet extends \Infinityloop\Utils\ObjectSet
{
    protected const INNER_CLASS = Directive::class;

    private string $location;
    private array $directiveTypes = [];

    public function __construct(array $data, string $location)
    {
        $this->location = $location;

        parent::__construct($data);
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

    protected function getKey(object $object) : ?string
    {
        \assert($object instanceof Directive);

        $directive = $object->getDirective();

        if (!\in_array($this->location, $directive->getLocations(), true)) {
            throw new \Graphpinator\Exception\Normalizer\MisplacedDirective();
        }

        if (!$directive->isRepeatable()) {
            if (\array_key_exists($directive->getName(), $this->directiveTypes)) {
                throw new \Graphpinator\Exception\Normalizer\DuplicatedDirective();
            }

            $this->directiveTypes[$directive->getName()] = true;
        }

        return parent::getKey($object);
    }
}
