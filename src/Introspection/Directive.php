<?php

declare(strict_types = 1);

namespace Graphpinator\Introspection;

use \Graphpinator\Typesystem\Contract\Directive as DirectiveDef;
use \Graphpinator\Typesystem\Field\ResolvableField;

#[\Graphpinator\Typesystem\Attribute\Description('Built-in introspection type')]
final class Directive extends \Graphpinator\Typesystem\Type
{
    protected const NAME = '__Directive';

    public function __construct(
        private \Graphpinator\Typesystem\Container $container,
    )
    {
        parent::__construct();
    }

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return $rawValue instanceof DirectiveDef;
    }

    protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
            new ResolvableField(
                'name',
                \Graphpinator\Typesystem\Container::String()->notNull(),
                static function (DirectiveDef $directive) : string {
                    return $directive->getName();
                },
            ),
            new ResolvableField(
                'description',
                \Graphpinator\Typesystem\Container::String(),
                static function (DirectiveDef $directive) : ?string {
                    return $directive->getDescription();
                },
            ),
            new ResolvableField(
                'locations',
                $this->container->getType('__DirectiveLocation')->notNullList(),
                static function (DirectiveDef $directive) : array {
                    return $directive->getLocations();
                },
            ),
            new ResolvableField(
                'args',
                $this->container->getType('__InputValue')->notNullList(),
                static function (DirectiveDef $directive) : \Graphpinator\Typesystem\Argument\ArgumentSet {
                    return $directive->getArguments();
                },
            ),
            new ResolvableField(
                'isRepeatable',
                \Graphpinator\Typesystem\Container::Boolean()->notNull(),
                static function (DirectiveDef $directive) : bool {
                    return $directive->isRepeatable();
                },
            ),
        ]);
    }
}
