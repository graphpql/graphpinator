<?php

declare(strict_types = 1);

namespace Graphpinator\Introspection;

final class Schema extends \Graphpinator\Type\Type
{
    protected const NAME = '__Schema';
    protected const DESCRIPTION = 'Built-in introspection type.';

    public function __construct(
        private \Graphpinator\Container\Container $container,
    )
    {
        parent::__construct();
    }

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return $rawValue instanceof \Graphpinator\Type\Schema;
    }

    protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Field\ResolvableFieldSet([
            new \Graphpinator\Field\ResolvableField(
                'description',
                \Graphpinator\Container\Container::String(),
                static function (\Graphpinator\Type\Schema $schema) : ?string {
                    return $schema->getDescription();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'types',
                $this->container->getType('__Type')->notNullList(),
                static function (\Graphpinator\Type\Schema $schema) : array {
                    return $schema->getContainer()->getTypes(true);
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'queryType',
                $this->container->getType('__Type')->notNull(),
                static function (\Graphpinator\Type\Schema $schema) : \Graphpinator\Type\Contract\Definition {
                    return $schema->getQuery();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'mutationType',
                $this->container->getType('__Type'),
                static function (\Graphpinator\Type\Schema $schema) : ?\Graphpinator\Type\Contract\Definition {
                    return $schema->getMutation();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'subscriptionType',
                $this->container->getType('__Type'),
                static function (\Graphpinator\Type\Schema $schema) : ?\Graphpinator\Type\Contract\Definition {
                    return $schema->getMutation();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'directives',
                $this->container->getType('__Directive')->notNullList(),
                static function (\Graphpinator\Type\Schema $schema) : array {
                    return $schema->getContainer()->getDirectives(true);
                },
            ),
        ]);
    }
}
