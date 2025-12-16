<?php

declare(strict_types = 1);

namespace Graphpinator\Introspection;

use Graphpinator\Typesystem\Argument\ArgumentSet;
use Graphpinator\Typesystem\Attribute\Description;
use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\Contract\Directive as DirectiveDef;
use Graphpinator\Typesystem\Field\ResolvableField;
use Graphpinator\Typesystem\Field\ResolvableFieldSet;
use Graphpinator\Typesystem\Type;

#[Description('Built-in introspection type')]
final class Directive extends Type
{
    protected const NAME = '__Directive';

    public function __construct(
        private Container $container,
    )
    {
        parent::__construct();
    }

    #[\Override]
    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return $rawValue instanceof DirectiveDef;
    }

    #[\Override]
    protected function getFieldDefinition() : ResolvableFieldSet
    {
        return new ResolvableFieldSet([
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
                static function (DirectiveDef $directive) : ArgumentSet {
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
