<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

use \Graphpinator\Typesystem\Argument\ArgumentSet;
use \Graphpinator\Typesystem\Location\ExecutableDirectiveLocation;
use \Graphpinator\Typesystem\Location\TypeSystemDirectiveLocation;

abstract class Directive implements \Graphpinator\Typesystem\Contract\Directive
{
    use \Graphpinator\Typesystem\Utils\THasDescription;

    protected const NAME = '';
    protected const REPEATABLE = false;
    private const INTERFACE_TO_LOCATION = [
        // Typesystem
        \Graphpinator\Typesystem\Location\SchemaLocation::class => [
            TypeSystemDirectiveLocation::SCHEMA,
        ],
        \Graphpinator\Typesystem\Location\ObjectLocation::class => [
            TypeSystemDirectiveLocation::OBJECT,
            TypeSystemDirectiveLocation::INTERFACE,
        ],
        \Graphpinator\Typesystem\Location\InputObjectLocation::class => [
            TypeSystemDirectiveLocation::INPUT_OBJECT,
        ],
        \Graphpinator\Typesystem\Location\UnionLocation::class => [
            TypeSystemDirectiveLocation::UNION,
        ],
        \Graphpinator\Typesystem\Location\EnumLocation::class => [
            TypeSystemDirectiveLocation::ENUM,
        ],
        \Graphpinator\Typesystem\Location\ScalarLocation::class => [
            TypeSystemDirectiveLocation::SCALAR,
        ],
        \Graphpinator\Typesystem\Location\ArgumentDefinitionLocation::class => [
            TypeSystemDirectiveLocation::ARGUMENT_DEFINITION,
            TypeSystemDirectiveLocation::INPUT_FIELD_DEFINITION,
        ],
        \Graphpinator\Typesystem\Location\FieldDefinitionLocation::class => [
            TypeSystemDirectiveLocation::FIELD_DEFINITION,
        ],
        \Graphpinator\Typesystem\Location\EnumItemLocation::class => [
            TypeSystemDirectiveLocation::ENUM_VALUE,
        ],
        // Executable
        \Graphpinator\Typesystem\Location\QueryLocation::class => [
            ExecutableDirectiveLocation::QUERY,
        ],
        \Graphpinator\Typesystem\Location\MutationLocation::class => [
            ExecutableDirectiveLocation::MUTATION,
        ],
        \Graphpinator\Typesystem\Location\SubscriptionLocation::class => [
            ExecutableDirectiveLocation::SUBSCRIPTION,
        ],
        \Graphpinator\Typesystem\Location\VariableDefinitionLocation::class => [
            ExecutableDirectiveLocation::VARIABLE_DEFINITION,
        ],
        \Graphpinator\Typesystem\Location\FragmentDefinitionLocation::class => [
            ExecutableDirectiveLocation::FRAGMENT_DEFINITION,
        ],
        \Graphpinator\Typesystem\Location\FieldLocation::class => [
            ExecutableDirectiveLocation::FIELD,
        ],
        \Graphpinator\Typesystem\Location\InlineFragmentLocation::class => [
            ExecutableDirectiveLocation::INLINE_FRAGMENT,
        ],
        \Graphpinator\Typesystem\Location\FragmentSpreadLocation::class => [
            ExecutableDirectiveLocation::FRAGMENT_SPREAD,
        ],
    ];

    protected ?ArgumentSet $arguments = null;

    final public function getName() : string
    {
        return static::NAME;
    }

    /**
     * @return array<(\Graphpinator\Typesystem\Location\ExecutableDirectiveLocation|\Graphpinator\Typesystem\Location\TypeSystemDirectiveLocation)>
     */
    final public function getLocations() : array
    {
        $locations = [];
        $reflection = new \ReflectionClass($this);

        foreach ($reflection->getInterfaces() as $interface) {
            if (\array_key_exists($interface->getName(), self::INTERFACE_TO_LOCATION)) {
                foreach (self::INTERFACE_TO_LOCATION[$interface->getName()] as $location) {
                    $locations[] = $location;
                }
            }
        }

        return $locations;
    }

    final public function isRepeatable() : bool
    {
        return static::REPEATABLE;
    }

    final public function getArguments() : ArgumentSet
    {
        if (!$this->arguments instanceof ArgumentSet) {
            $this->arguments = $this->getFieldDefinition();
            $this->afterGetFieldDefinition();
        }

        return $this->arguments;
    }

    final public function accept(\Graphpinator\Typesystem\Contract\EntityVisitor $visitor) : mixed
    {
        return $visitor->visitDirective($this);
    }

    abstract protected function getFieldDefinition() : ArgumentSet;

    /**
     * This function serves to prevent infinite cycles.
     *
     * It doesn't have to be used at all, unless directive have arguments with directive cycles.
     * E.g. IntConstraintDirective::oneOf -> ListConstraintDirective::minItems -> IntConstraintDirective::oneOf.
     */
    protected function afterGetFieldDefinition() : void
    {
    }
}
