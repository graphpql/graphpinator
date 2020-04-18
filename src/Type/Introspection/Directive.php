<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Introspection;

final class Directive extends \Graphpinator\Type\Type
{
    protected const NAME = '__Directive';
    protected const DESCRIPTION = 'Built-in introspection type.';

    private \Graphpinator\Type\Container\Container $container;

    public function __construct(\Graphpinator\Type\Container\Container $container)
    {
        parent::__construct();

        $this->container = $container;
    }

    protected function validateNonNullValue($rawValue) : bool
    {
        return $rawValue instanceof \Graphpinator\Directive\Directive;
    }

    protected function getFieldDefinition(): \Graphpinator\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Field\ResolvableFieldSet([
            new \Graphpinator\Field\ResolvableField(
                'name',
                \Graphpinator\Type\Container\Container::String()->notNull(),
                static function (\Graphpinator\Directive\Directive $directive) : string {
                    return $directive->getName();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'description',
                \Graphpinator\Type\Container\Container::String(),
                static function (\Graphpinator\Directive\Directive $directive) : ?string {
                    return $directive->getDescription();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'locations',
                $this->container->introspectionDirectiveLocation()->notNullList(),
                static function (\Graphpinator\Directive\Directive $directive) : array {
                    return $directive->getLocations();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'args',
                $this->container->introspectionInputValue()->notNullList(),
                static function (\Graphpinator\Directive\Directive $directive) : \Graphpinator\Argument\ArgumentSet {
                    return $directive->getArguments();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'isRepeatable',
                \Graphpinator\Type\Container\Container::Boolean()->notNull(),
                static function (\Graphpinator\Directive\Directive $directive) : bool {
                    return $directive->isRepeatable();
                },
            ),
        ]);
    }
}
