<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Introspection;

final class Directive extends \Graphpinator\Type\Type
{
    protected const NAME = '__Directive';
    protected const DESCRIPTION = 'Built-in introspection type.';

    public function __construct(
        private \Graphpinator\Container\Container $container,
    )
    {
        parent::__construct();
    }

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return $rawValue instanceof \Graphpinator\Directive\Directive;
    }

    protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Field\ResolvableFieldSet([
            new \Graphpinator\Field\ResolvableField(
                'name',
                \Graphpinator\Container\Container::String()->notNull(),
                static function (\Graphpinator\Directive\Directive $directive) : string {
                    return $directive->getName();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'description',
                \Graphpinator\Container\Container::String(),
                static function (\Graphpinator\Directive\Directive $directive) : ?string {
                    return $directive->getDescription();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'locations',
                $this->container->getType('__DirectiveLocation')->notNullList(),
                static function (\Graphpinator\Directive\Directive $directive) : array {
                    return $directive->getLocations();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'args',
                $this->container->getType('__InputValue')->notNullList(),
                static function (\Graphpinator\Directive\Directive $directive) : \Graphpinator\Argument\ArgumentSet {
                    return $directive->getArguments();
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'isRepeatable',
                \Graphpinator\Container\Container::Boolean()->notNull(),
                static function (\Graphpinator\Directive\Directive $directive) : bool {
                    return $directive->isRepeatable();
                },
            ),
        ]);
    }
}
