<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

abstract class Directive implements \Graphpinator\Typesystem\Contract\Directive
{
    use \Nette\SmartObject;

    protected const NAME = '';
    protected const DESCRIPTION = null;
    protected const REPEATABLE = false;

    protected ?\Graphpinator\Typesystem\Argument\ArgumentSet $arguments = null;

    final public function getName() : string
    {
        return static::NAME;
    }

    final public function getDescription() : ?string
    {
        return static::DESCRIPTION;
    }

    final public function getLocations() : array
    {
        $locations = [];
        $reflection = new \ReflectionClass($this);

        foreach ($reflection->getInterfaces() as $interface) {
            if (\array_key_exists($interface->getName(), self::INTERFACE_TO_LOCATION)) {
                $locations = \array_merge($locations, self::INTERFACE_TO_LOCATION[$interface->getName()]);
            }
        }

        return $locations;
    }

    final public function isRepeatable() : bool
    {
        return static::REPEATABLE;
    }

    final public function getArguments() : \Graphpinator\Typesystem\Argument\ArgumentSet
    {
        if (!$this->arguments instanceof \Graphpinator\Typesystem\Argument\ArgumentSet) {
            $this->arguments = $this->getFieldDefinition();
            $this->afterGetFieldDefinition();
        }

        return $this->arguments;
    }

    final public function accept(\Graphpinator\Typesystem\Contract\EntityVisitor $visitor) : mixed
    {
        return $visitor->visitDirective($this);
    }

    abstract protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet;

    /**
     * This function serves to prevent infinite cycles.
     *
     * It doesn't have to be used at all, unless directive have arguments with directive cycles.
     * Eg. IntConstraintDirective::oneOf -> ListConstraintDirective::minItems -> IntConstraintDirective::oneOf.
     */
    protected function afterGetFieldDefinition() : void
    {
    }
}
