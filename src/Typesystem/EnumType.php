<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

abstract class EnumType extends \Graphpinator\Typesystem\Contract\LeafType
{
    use \Graphpinator\Typesystem\Utils\THasDirectives;

    public function __construct(
        protected \Graphpinator\Typesystem\EnumItem\EnumItemSet $options,
    )
    {
        $this->directiveUsages = new \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet();
    }

    final public static function fromConstants() : \Graphpinator\Typesystem\EnumItem\EnumItemSet
    {
        $values = [];

        foreach ((new \ReflectionClass(static::class))->getReflectionConstants() as $constant) {
            $value = $constant->getValue();

            if (!$constant->isPublic()) {
                continue;
            }

            $values[] = new \Graphpinator\Typesystem\EnumItem\EnumItem($value, $constant->getDocComment()
                ? \trim($constant->getDocComment(), '/* ')
                : null);
        }

        return new \Graphpinator\Typesystem\EnumItem\EnumItemSet($values);
    }

    final public static function fromEnum(string $enumClass) : \Graphpinator\Typesystem\EnumItem\EnumItemSet
    {
        $values = [];
        $ref = new \ReflectionEnum($enumClass);

        if ((string) $ref->getBackingType() !== 'string') {
            throw new \InvalidArgumentException('Enum must be backed by string.');
        }

        foreach ($ref->getCases() as $case) {
            $values[] = new \Graphpinator\Typesystem\EnumItem\EnumItem($case->getBackingValue(), $case->getDocComment()
                ? \trim($case->getDocComment(), '/* ')
                : null);
        }

        return new \Graphpinator\Typesystem\EnumItem\EnumItemSet($values);
    }

    final public function getItems() : \Graphpinator\Typesystem\EnumItem\EnumItemSet
    {
        return $this->options;
    }

    final public function accept(\Graphpinator\Typesystem\Contract\NamedTypeVisitor $visitor) : mixed
    {
        return $visitor->visitEnum($this);
    }

    final public function validateNonNullValue(mixed $rawValue) : bool
    {
        return \is_string($rawValue) && $this->options->offsetExists($rawValue);
    }

    final public function addDirective(
        \Graphpinator\Typesystem\Location\EnumLocation $directive,
        array $arguments = [],
    ) : static
    {
        $this->directiveUsages[] = new \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsage($directive, $arguments);

        return $this;
    }
}
