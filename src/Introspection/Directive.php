<?php

declare(strict_types = 1);

namespace Graphpinator\Introspection;

use \Graphpinator\Typesystem\Container;
use \Graphpinator\Typesystem\Contract\Directive as TDirective;
use \Graphpinator\Typesystem\Field\ResolvableField;
use \Graphpinator\Typesystem\Field\ResolvableFieldSet;

final class Directive extends \Graphpinator\Typesystem\Type
{
    protected const NAME = '__Directive';
    protected const DESCRIPTION = 'Built-in introspection type.';

    public function __construct(
        private Container $container,
    )
    {
        parent::__construct();
    }

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return $rawValue instanceof TDirective;
    }

    protected function getFieldDefinition() : ResolvableFieldSet
    {
        return new ResolvableFieldSet([
            new ResolvableField(
                'name',
                Container::String()->notNull(),
                static function (TDirective $directive) : string {
                    return $directive->getName();
                },
            ),
            new ResolvableField(
                'description',
                Container::String(),
                static function (TDirective $directive) : ?string {
                    return $directive->getDescription();
                },
            ),
            new ResolvableField(
                'locations',
                $this->container->getType('__DirectiveLocation')->notNullList(),
                static function (TDirective $directive) : array {
                    return $directive->getLocations();
                },
            ),
            new ResolvableField(
                'args',
                $this->container->getType('__InputValue')->notNullList(),
                static function (TDirective $directive) : \Graphpinator\Typesystem\Argument\ArgumentSet {
                    return $directive->getArguments();
                },
            ),
            new ResolvableField(
                'isRepeatable',
                Container::Boolean()->notNull(),
                static function (TDirective $directive) : bool {
                    return $directive->isRepeatable();
                },
            ),
        ]);
    }
}
