<?php

declare(strict_types = 1);

namespace Graphpinator\Introspection;

use \Graphpinator\Typesystem\Contract\Type;
use \Graphpinator\Typesystem\Field\ResolvableField;
use \Graphpinator\Typesystem\Schema as SchemaDef;

#[\Graphpinator\Typesystem\Attribute\Description('Built-in introspection type')]
final class Schema extends \Graphpinator\Typesystem\Type
{
    protected const NAME = '__Schema';

    public function __construct(
        private \Graphpinator\Typesystem\Container $container,
    )
    {
        parent::__construct();
    }

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return $rawValue instanceof SchemaDef;
    }

    protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
            ResolvableField::create(
                'description',
                \Graphpinator\Typesystem\Container::String(),
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
                    return $schema->getMutation();
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
