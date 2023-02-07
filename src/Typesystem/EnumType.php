<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

use \Graphpinator\Typesystem\EnumItem\EnumItem;
use \Graphpinator\Typesystem\EnumItem\EnumItemSet;

abstract class EnumType extends \Graphpinator\Typesystem\Contract\LeafType
{
    use \Graphpinator\Typesystem\Utils\THasDirectives;

    public function __construct(
        protected EnumItemSet $options,
    )
    {
        $this->directiveUsages = new \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet();
    }

    final public static function fromConstants() : EnumItemSet
    {
        $values = [];

        foreach ((new \ReflectionClass(static::class))->getReflectionConstants() as $constant) {
            $value = $constant->getValue();

            if (!$constant->isPublic()) {
                continue;
            }

            $values[] = new EnumItem($value, self::getItemDescription($constant));
        }

        return new EnumItemSet($values);
    }

    final public static function fromEnum(string $enumClass) : EnumItemSet
    {
        $values = [];
        $ref = new \ReflectionEnum($enumClass);

        if ((string) $ref->getBackingType() !== 'string') {
            throw new \InvalidArgumentException('Enum must be backed by string.');
        }

        foreach ($ref->getCases() as $case) {
            $values[] = new EnumItem($case->getBackingValue(), self::getItemDescription($case));
        }

        return new EnumItemSet($values, $enumClass);
    }

    final public function getItems() : EnumItemSet
    {
        return $this->options;
    }

    final public function getEnumClass() : ?string
    {
        return $this->options->getEnumClass();
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

    private static function getItemDescription(\ReflectionClassConstant|\ReflectionEnumBackedCase $reflection) : ?string
    {
        $attrs = $reflection->getAttributes(\Graphpinator\Typesystem\Attribute\Description::class);

        if (\count($attrs) === 1) {
            $attr = $attrs[0]->newInstance();
            \assert($attr instanceof \Graphpinator\Typesystem\Attribute\Description);

            return $attr->getValue();
        }

        $comment = $reflection->getDocComment();

        if ($comment) {
            return \trim($comment, '/* ');
        }

        return null;
    }
}
