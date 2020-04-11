<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Introspection;

final class Schema extends \Graphpinator\Type\Type
{
    protected const NAME = '__Schema';
    protected const DESCRIPTION = 'Built-in introspection type.';

    public function __construct()
    {
        parent::__construct();
    }

    public function validateNonNullValue($rawValue) : bool
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
                \Graphpinator\Type\Container\Container::introspectionType()->notNullList(),
                static function (\Graphpinator\Type\Schema $schema) : array {
                    return $schema->getTypeContainer()->getAllTypes();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'queryType',
                \Graphpinator\Type\Container\Container::introspectionType()->notNull(),
                static function (\Graphpinator\Type\Schema $schema) : \Graphpinator\Type\Contract\Definition {
                    return $schema->getQuery();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'mutationType',
                \Graphpinator\Type\Container\Container::introspectionType(),
                static function (\Graphpinator\Type\Schema $schema) : ?\Graphpinator\Type\Contract\Definition {
                    return $schema->getMutation();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'subscriptionType',
                \Graphpinator\Type\Container\Container::introspectionType(),
                static function (\Graphpinator\Type\Schema $schema) : ?\Graphpinator\Type\Contract\Definition {
                    return $schema->getMutation();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'directives',
                \Graphpinator\Type\Container\Container::introspectionDirective()->notNullList(),
                static function (\Graphpinator\Type\Schema $schema) : array {
                    return $schema->getTypeContainer()->getAllDirectives();
                },
            ),
        ]);
    }
}
