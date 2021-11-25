<?php

declare(strict_types = 1);

namespace Graphpinator\Introspection;

use \Graphpinator\Typesystem\Contract\Type;
use \Graphpinator\Typesystem\Field\ResolvableField;
use \Graphpinator\Typesystem\Schema as TSchema;

final class Schema extends \Graphpinator\Typesystem\Type
{
    protected const NAME = '__Schema';
    protected const DESCRIPTION = 'Built-in introspection type.';

    public function __construct(
        private \Graphpinator\Typesystem\Container $container,
    )
    {
        parent::__construct();
    }

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return $rawValue instanceof TSchema;
    }

    protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
            new ResolvableField(
                'description',
                \Graphpinator\Typesystem\Container::String(),
                static function (TSchema $schema) : ?string {
                    return $schema->getDescription();
                },
            ),
            new ResolvableField(
                'types',
                $this->container->getType('__Type')->notNullList(),
                static function (TSchema $schema) : array {
                    return $schema->getContainer()->getTypes(true);
                },
            ),
            new ResolvableField(
                'queryType',
                $this->container->getType('__Type')->notNull(),
                static function (TSchema $schema) : Type {
                    return $schema->getQuery();
                },
            ),
            new ResolvableField(
                'mutationType',
                $this->container->getType('__Type'),
                static function (TSchema $schema) : ?Type {
                    return $schema->getMutation();
                },
            ),
            new ResolvableField(
                'subscriptionType',
                $this->container->getType('__Type'),
                static function (TSchema $schema) : ?Type {
                    return $schema->getMutation();
                },
            ),
            new ResolvableField(
                'directives',
                $this->container->getType('__Directive')->notNullList(),
                static function (TSchema $schema) : array {
                    return $schema->getContainer()->getDirectives(true);
                },
            ),
        ]);
    }
}
