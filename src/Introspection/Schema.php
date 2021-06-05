<?php

declare(strict_types = 1);

namespace Graphpinator\Introspection;

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
        return $rawValue instanceof \Graphpinator\Typesystem\Schema;
    }

    protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
            new \Graphpinator\Typesystem\Field\ResolvableField(
                'description',
                \Graphpinator\Typesystem\Container::String(),
                static function (\Graphpinator\Typesystem\Schema $schema) : ?string {
                    return $schema->getDescription();
                },
            ),
            new \Graphpinator\Typesystem\Field\ResolvableField(
                'types',
                $this->container->getType('__Type')->notNullList(),
                static function (\Graphpinator\Typesystem\Schema $schema) : array {
                    return $schema->getContainer()->getTypes(true);
                },
            ),
            new \Graphpinator\Typesystem\Field\ResolvableField(
                'queryType',
                $this->container->getType('__Type')->notNull(),
                static function (\Graphpinator\Typesystem\Schema $schema) : \Graphpinator\Typesystem\Contract\Type {
                    return $schema->getQuery();
                },
            ),
            new \Graphpinator\Typesystem\Field\ResolvableField(
                'mutationType',
                $this->container->getType('__Type'),
                static function (\Graphpinator\Typesystem\Schema $schema) : ?\Graphpinator\Typesystem\Contract\Type {
                    return $schema->getMutation();
                },
            ),
            new \Graphpinator\Typesystem\Field\ResolvableField(
                'subscriptionType',
                $this->container->getType('__Type'),
                static function (\Graphpinator\Typesystem\Schema $schema) : ?\Graphpinator\Typesystem\Contract\Type {
                    return $schema->getMutation();
                },
            ),
            new \Graphpinator\Typesystem\Field\ResolvableField(
                'directives',
                $this->container->getType('__Directive')->notNullList(),
                static function (\Graphpinator\Typesystem\Schema $schema) : array {
                    return $schema->getContainer()->getDirectives(true);
                },
            ),
        ]);
    }
}
