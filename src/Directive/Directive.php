<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

abstract class Directive implements \Graphpinator\Directive\Contract\Definition
{
    use \Nette\SmartObject;

    protected const NAME = '';
    protected const DESCRIPTION = null;
    protected const REPEATABLE = false;

    protected ?\Graphpinator\Argument\ArgumentSet $arguments = null;

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
            switch ($interface->getName()) {
                case \Graphpinator\Directive\Contract\ObjectLocation::class:
                    $locations[] = TypeSystemDirectiveLocation::OBJECT;
                    $locations[] = TypeSystemDirectiveLocation::INTERFACE;

                    break;
                case \Graphpinator\Directive\Contract\InputObjectLocation::class:
                    $locations[] = TypeSystemDirectiveLocation::INPUT_OBJECT;

                    break;
                case \Graphpinator\Directive\Contract\ArgumentDefinitionLocation::class:
                    $locations[] = TypeSystemDirectiveLocation::ARGUMENT_DEFINITION;
                    $locations[] = TypeSystemDirectiveLocation::INPUT_FIELD_DEFINITION;

                    break;
                case \Graphpinator\Directive\Contract\FieldDefinitionLocation::class:
                    $locations[] = TypeSystemDirectiveLocation::FIELD_DEFINITION;

                    break;
                case \Graphpinator\Directive\Contract\EnumItemLocation::class:
                    $locations[] = TypeSystemDirectiveLocation::ENUM_VALUE;

                    break;
                case \Graphpinator\Directive\Contract\FieldLocation::class:
                    $locations[] = ExecutableDirectiveLocation::FIELD;
                    $locations[] = ExecutableDirectiveLocation::INLINE_FRAGMENT;
                    $locations[] = ExecutableDirectiveLocation::FRAGMENT_SPREAD;

                    break;
            }
        }

        return $locations;
    }

    final public function isRepeatable() : bool
    {
        return static::REPEATABLE;
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
