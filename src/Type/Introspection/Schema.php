<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Introspection;

final class Schema extends \Graphpinator\Type\Type
{
    protected const NAME = '__Schema';
    protected const DESCRIPTION = 'Built-in introspection type.';

    private \Graphpinator\Type\Container\Container $container;

    public function __construct(\Graphpinator\Type\Container\Container $container)
    {
        parent::__construct();

        $this->container = $container;
    }

    protected function validateNonNullValue($rawValue) : bool
    {
        return $rawValue instanceof \Graphpinator\Type\Schema;
    }

    protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Field\ResolvableFieldSet([
            new \Graphpinator\Field\ResolvableField(
                'description',
                \Graphpinator\Type\Container\Container::String(),
                static function (\Graphpinator\Type\Schema $schema) : ?string {
                    return $schema->getDescription();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'types',
                $this->container->introspectionType()->notNullList(),
                static function (\Graphpinator\Type\Schema $schema) : array {
                    return $schema->getContainer()->getTypes();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'queryType',
                $this->container->introspectionType()->notNull(),
                static function (\Graphpinator\Type\Schema $schema) : \Graphpinator\Type\Contract\Definition {
                    return $schema->getQuery();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'mutationType',
                $this->container->introspectionType(),
                static function (\Graphpinator\Type\Schema $schema) : ?\Graphpinator\Type\Contract\Definition {
                    return $schema->getMutation();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'subscriptionType',
                $this->container->introspectionType(),
                static function (\Graphpinator\Type\Schema $schema) : ?\Graphpinator\Type\Contract\Definition {
                    return $schema->getMutation();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'directives',
                $this->container->introspectionDirective()->notNullList(),
                static function (\Graphpinator\Type\Schema $schema) : array {
                    return $schema->getContainer()->getDirectives();
                },
            ),
        ]);
    }
}
