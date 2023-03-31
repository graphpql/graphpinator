<?php

declare(strict_types = 1);

namespace Graphpinator\Introspection;

use \Graphpinator\Typesystem\Container;
use \Graphpinator\Typesystem\Contract\Directive as DirectiveDef;
use \Graphpinator\Typesystem\Field\ResolvableField;

#[\Graphpinator\Typesystem\Attribute\Description('Built-in introspection type')]
final class Directive extends \Graphpinator\Typesystem\Type
{
    protected const NAME = '__Directive';

    public function __construct(
        private Container $container,
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
            ResolvableField::create(
                'name',
                Container::String()->notNull(),
                static function (DirectiveDef $directive) : string {
                    return $directive->getName();
                },
            ),
            ResolvableField::create(
                'description',
                Container::String(),
                static function (DirectiveDef $directive) : ?string {
                    return $directive->getDescription();
                },
            ),
            ResolvableField::create(
                'locations',
                $this->container->getType('__DirectiveLocation')->notNullList(),
                static function (DirectiveDef $directive) : array {
                    return $directive->getLocations();
                },
            ),
            ResolvableField::create(
                'args',
                $this->container->getType('__InputValue')->notNullList(),
                static function (DirectiveDef $directive) : \Graphpinator\Typesystem\Argument\ArgumentSet {
                    return $directive->getArguments();
                },
            ),
            ResolvableField::create(
                'isRepeatable',
                Container::Boolean()->notNull(),
                static function (DirectiveDef $directive) : bool {
                    return $directive->isRepeatable();
                },
            ),
        ]);
    }
}
