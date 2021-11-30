<?php

declare(strict_types = 1);

namespace Graphpinator\Introspection;

#[\Graphpinator\Typesystem\Attribute\Description('Built-in introspection type.')]
final class Directive extends \Graphpinator\Typesystem\Type
{
    protected const NAME = '__Directive';

    public function __construct(
        private \Graphpinator\Typesystem\Container $container,
    )
    {
        parent::__construct();
    }

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return $rawValue instanceof \Graphpinator\Typesystem\Contract\Directive;
    }

    protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
            new \Graphpinator\Typesystem\Field\ResolvableField(
                'name',
                \Graphpinator\Typesystem\Container::String()->notNull(),
                static function (\Graphpinator\Typesystem\Contract\Directive $directive) : string {
                    return $directive->getName();
                },
            ),
            new \Graphpinator\Typesystem\Field\ResolvableField(
                'description',
                \Graphpinator\Typesystem\Container::String(),
                static function (\Graphpinator\Typesystem\Contract\Directive $directive) : ?string {
                    return $directive->getDescription();
                },
            ),
            new \Graphpinator\Typesystem\Field\ResolvableField(
                'locations',
                $this->container->getType('__DirectiveLocation')->notNullList(),
                static function (\Graphpinator\Typesystem\Contract\Directive $directive) : array {
                    return $directive->getLocations();
                },
            ),
            new \Graphpinator\Typesystem\Field\ResolvableField(
                'args',
                $this->container->getType('__InputValue')->notNullList(),
                static function (\Graphpinator\Typesystem\Contract\Directive $directive) : \Graphpinator\Typesystem\Argument\ArgumentSet {
                    return $directive->getArguments();
                },
            ),
            new \Graphpinator\Typesystem\Field\ResolvableField(
                'isRepeatable',
                \Graphpinator\Typesystem\Container::Boolean()->notNull(),
                static function (\Graphpinator\Typesystem\Contract\Directive $directive) : bool {
                    return $directive->isRepeatable();
                },
            ),
        ]);
    }
}
