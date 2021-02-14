<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

abstract class Directive implements
    \Graphpinator\Typesystem\Entity,
    \Graphpinator\Directive\Contract\Definition
{
    use \Nette\SmartObject;

    protected const NAME = '';
    protected const DESCRIPTION = null;

    protected array $locations;
    protected bool $repeatable;
    protected ?\Graphpinator\Argument\ArgumentSet $arguments = null;

    public function __construct(array $locations, bool $repeatable)
    {
        $this->locations = $locations;
        $this->repeatable = $repeatable;
    }

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
        return $this->locations;
    }

    final public function isRepeatable() : bool
    {
        return $this->repeatable;
    }

    final public function getArguments() : \Graphpinator\Argument\ArgumentSet
    {
        if (!$this->arguments instanceof \Graphpinator\Argument\ArgumentSet) {
            $this->arguments = $this->getFieldDefinition();
            $this->appendDirectives();
        }

        return $this->arguments;
    }

    final public function accept(\Graphpinator\Typesystem\EntityVisitor $visitor) : mixed
    {
        return $visitor->visitDirective($this);
    }

    abstract protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet;

    /**
     * This function serves to prevent infinite cycles.
     *
     * It doesn't have to be used at all, unless directive have arguments with directive cycles.
     * Eg. IntConstraintDirective::oneOf -> ListConstraintDirective::minItems -> IntConstraintDirective::oneOf.
     */
    protected function appendDirectives() : void
    {
    }
}
