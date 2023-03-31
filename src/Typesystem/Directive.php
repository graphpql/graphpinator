<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

use \Graphpinator\Typesystem\Argument\ArgumentSet;
use \Graphpinator\Typesystem\Location\TypeSystemDirectiveLocation;
use \Graphpinator\Typesystem\Location\ExecutableDirectiveLocation;

abstract class Directive implements \Graphpinator\Typesystem\Contract\Directive
{
    use \Nette\SmartObject;
    use \Graphpinator\Typesystem\Utils\THasDescription;

    protected const NAME = '';
    protected const REPEATABLE = false;
    private const INTERFACE_TO_LOCATION = [
        // Typesystem
        \Graphpinator\Typesystem\Location\SchemaLocation::class => [
            TypeSystemDirectiveLocation::SCHEMA,
        ],
        \Graphpinator\Typesystem\Location\ObjectLocation::class => [
            TypesystemDirectiveLocation::OBJECT,
            TypesystemDirectiveLocation::INTERFACE,
        ],
        \Graphpinator\Typesystem\Location\InputObjectLocation::class => [
            TypesystemDirectiveLocation::INPUT_OBJECT,
        ],
        \Graphpinator\Typesystem\Location\UnionLocation::class => [
            TypesystemDirectiveLocation::UNION,
        ],
        \Graphpinator\Typesystem\Location\EnumLocation::class => [
            TypesystemDirectiveLocation::ENUM,
        ],
        \Graphpinator\Typesystem\Location\ScalarLocation::class => [
            TypesystemDirectiveLocation::SCALAR,
        ],
        \Graphpinator\Typesystem\Location\ArgumentDefinitionLocation::class => [
            TypesystemDirectiveLocation::ARGUMENT_DEFINITION,
            TypesystemDirectiveLocation::INPUT_FIELD_DEFINITION,
        ],
        \Graphpinator\Typesystem\Location\FieldDefinitionLocation::class => [
            TypesystemDirectiveLocation::FIELD_DEFINITION,
        ],
        \Graphpinator\Typesystem\Location\EnumItemLocation::class => [
            TypesystemDirectiveLocation::ENUM_VALUE,
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
     * @return array<string>
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
