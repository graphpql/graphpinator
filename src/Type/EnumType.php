<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

abstract class EnumType extends \Graphpinator\Type\Contract\LeafDefinition
{
    use \Graphpinator\Utils\THasDirectives;

    public function __construct(
        protected \Graphpinator\EnumItem\EnumItemSet $options,
    )
    {
        $this->directiveUsages = new \Graphpinator\DirectiveUsage\DirectiveUsageSet();
    }

    final public static function fromConstants() : \Graphpinator\EnumItem\EnumItemSet
    {
        $values = [];

        foreach ((new \ReflectionClass(static::class))->getReflectionConstants() as $constant) {
            $value = $constant->getValue();

            if (!$constant->isPublic()) {
                continue;
            }

            $values[] = new \Graphpinator\EnumItem\EnumItem($value, $constant->getDocComment()
                ? \trim($constant->getDocComment(), '/* ')
                : null);
        }

        return new \Graphpinator\EnumItem\EnumItemSet($values);
    }

    final public function getItems() : \Graphpinator\EnumItem\EnumItemSet
    {
        return $this->options;
    }

    final public function accept(\Graphpinator\Typesystem\NamedTypeVisitor $visitor) : mixed
    {
        return $visitor->visitEnum($this);
    }

    final public function validateNonNullValue(mixed $rawValue) : bool
    {
        return \is_string($rawValue) && $this->options->offsetExists($rawValue);
    }

    final public function addDirective(
        \Graphpinator\Directive\Contract\EnumLocation $directive,
        array $arguments = [],
    ) : static
    {
        $usage = new \Graphpinator\DirectiveUsage\DirectiveUsage($directive, $arguments);
        $this->directiveUsages[] = $usage;

        return $this;
    }
}
