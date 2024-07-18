<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

use Graphpinator\Typesystem\Argument\ArgumentSet;
use Graphpinator\Typesystem\Contract\EntityVisitor;
use Graphpinator\Typesystem\Location\ArgumentDefinitionLocation;
use Graphpinator\Typesystem\Location\EnumItemLocation;
use Graphpinator\Typesystem\Location\EnumLocation;
use Graphpinator\Typesystem\Location\ExecutableDirectiveLocation;
use Graphpinator\Typesystem\Location\FieldDefinitionLocation;
use Graphpinator\Typesystem\Location\FieldLocation;
use Graphpinator\Typesystem\Location\FragmentDefinitionLocation;
use Graphpinator\Typesystem\Location\FragmentSpreadLocation;
use Graphpinator\Typesystem\Location\InlineFragmentLocation;
use Graphpinator\Typesystem\Location\InputObjectLocation;
use Graphpinator\Typesystem\Location\MutationLocation;
use Graphpinator\Typesystem\Location\ObjectLocation;
use Graphpinator\Typesystem\Location\QueryLocation;
use Graphpinator\Typesystem\Location\ScalarLocation;
use Graphpinator\Typesystem\Location\SchemaLocation;
use Graphpinator\Typesystem\Location\SubscriptionLocation;
use Graphpinator\Typesystem\Location\TypeSystemDirectiveLocation;
use Graphpinator\Typesystem\Location\UnionLocation;
use Graphpinator\Typesystem\Location\VariableDefinitionLocation;
use Graphpinator\Typesystem\Utils\THasDescription;

abstract class Directive implements \Graphpinator\Typesystem\Contract\Directive
{
    use THasDescription;

    protected const NAME = '';
    protected const REPEATABLE = false;
    private const INTERFACE_TO_LOCATION = [
        // Typesystem
        SchemaLocation::class => [
            TypeSystemDirectiveLocation::SCHEMA,
        ],
        ObjectLocation::class => [
            TypeSystemDirectiveLocation::OBJECT,
            TypeSystemDirectiveLocation::INTERFACE,
        ],
        InputObjectLocation::class => [
            TypeSystemDirectiveLocation::INPUT_OBJECT,
        ],
        UnionLocation::class => [
            TypeSystemDirectiveLocation::UNION,
        ],
        EnumLocation::class => [
            TypeSystemDirectiveLocation::ENUM,
        ],
        ScalarLocation::class => [
            TypeSystemDirectiveLocation::SCALAR,
        ],
        ArgumentDefinitionLocation::class => [
            TypeSystemDirectiveLocation::ARGUMENT_DEFINITION,
            TypeSystemDirectiveLocation::INPUT_FIELD_DEFINITION,
        ],
        FieldDefinitionLocation::class => [
            TypeSystemDirectiveLocation::FIELD_DEFINITION,
        ],
        EnumItemLocation::class => [
            TypeSystemDirectiveLocation::ENUM_VALUE,
        ],
        // Executable
        QueryLocation::class => [
            ExecutableDirectiveLocation::QUERY,
        ],
        MutationLocation::class => [
            ExecutableDirectiveLocation::MUTATION,
        ],
        SubscriptionLocation::class => [
            ExecutableDirectiveLocation::SUBSCRIPTION,
        ],
        VariableDefinitionLocation::class => [
            ExecutableDirectiveLocation::VARIABLE_DEFINITION,
        ],
        FragmentDefinitionLocation::class => [
            ExecutableDirectiveLocation::FRAGMENT_DEFINITION,
        ],
        FieldLocation::class => [
            ExecutableDirectiveLocation::FIELD,
        ],
        InlineFragmentLocation::class => [
            ExecutableDirectiveLocation::INLINE_FRAGMENT,
        ],
        FragmentSpreadLocation::class => [
            ExecutableDirectiveLocation::FRAGMENT_SPREAD,
        ],
    ];

    protected ?ArgumentSet $arguments = null;

    final public function getName() : string
    {
        return static::NAME;
    }

    /**
     * @return array<(ExecutableDirectiveLocation|TypeSystemDirectiveLocation)>
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

    final public function accept(EntityVisitor $visitor) : mixed
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
