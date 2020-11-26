<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Directive;

/**
 * @method \Graphpinator\Normalizer\Directive\Directive current() : object
 * @method \Graphpinator\Normalizer\Directive\Directive offsetGet($offset) : object
 */
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

    public function offsetSet($offset, $value) : void
    {
        \assert($value instanceof Directive);

        $directive = $value->getDirective();

        if (!\in_array($this->location, $directive->getLocations(), true)) {
            throw new \Graphpinator\Exception\Normalizer\MisplacedDirective();
        }

        if (!$directive->isRepeatable()) {
            if (\array_key_exists($directive->getName(), $this->directiveTypes)) {
                throw new \Graphpinator\Exception\Normalizer\DuplicatedDirective();
            }

            $this->directiveTypes[$directive->getName()] = true;
        }

        parent::offsetSet($offset, $value);
    }
}
