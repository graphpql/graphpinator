<?php

declare(strict_types = 1);

namespace Graphpinator\Introspection;

use Graphpinator\Typesystem\Attribute\Description;
use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\Field\ResolvableField;
use Graphpinator\Typesystem\Field\ResolvableFieldSet;
use Graphpinator\Typesystem\Schema as SchemaDef;
use Graphpinator\Typesystem\Type;

#[Description('Built-in introspection type')]
final class Schema extends Type
{
    protected const NAME = '__Schema';

    public function __construct(
        private Container $container,
    )
    {
        parent::__construct();
    }

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return $rawValue instanceof SchemaDef;
    }

    protected function getFieldDefinition() : ResolvableFieldSet
    {
        return new ResolvableFieldSet([
            ResolvableField::create(
                'description',
                Container::String(),
                static function (SchemaDef $schema) : ?string {
                    return $schema->getDescription();
                },
            ),
            ResolvableField::create(
                'types',
                $this->container->getType('__Type')->notNullList(),
                static function (SchemaDef $schema) : array {
                    return $schema->getContainer()->getTypes(true);
                },
            ),
            ResolvableField::create(
                'queryType',
                $this->container->getType('__Type')->notNull(),
                static function (SchemaDef $schema) : Type {
                    return $schema->getQuery();
                },
            ),
            ResolvableField::create(
                'mutationType',
                $this->container->getType('__Type'),
                static function (SchemaDef $schema) : ?Type {
                    return $schema->getMutation();
                },
            ),
            ResolvableField::create(
                'subscriptionType',
                $this->container->getType('__Type'),
                static function (SchemaDef $schema) : ?Type {
                    return $schema->getSubscription();
                },
            ),
            ResolvableField::create(
                'directives',
                $this->container->getType('__Directive')->notNullList(),
                static function (SchemaDef $schema) : array {
                    return $schema->getContainer()->getDirectives(true);
                },
            ),
        ]);
    }
}
