<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

use Graphpinator\Typesystem\Attribute\Description;
use Graphpinator\Typesystem\Contract\LeafType;
use Graphpinator\Typesystem\Contract\NamedTypeVisitor;
use Graphpinator\Typesystem\DirectiveUsage\DirectiveUsage;
use Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet;
use Graphpinator\Typesystem\EnumItem\EnumItem;
use Graphpinator\Typesystem\EnumItem\EnumItemSet;
use Graphpinator\Typesystem\Location\EnumLocation;
use Graphpinator\Typesystem\Utils\THasDirectives;

abstract class EnumType extends LeafType
{
    use THasDirectives;

    public function __construct(
        protected EnumItemSet $options,
    )
    {
        $this->directiveUsages = new DirectiveUsageSet();
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
            \assert($case instanceof \ReflectionEnumBackedCase);

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

    final public function accept(NamedTypeVisitor $visitor) : mixed
    {
        return $visitor->visitEnum($this);
    }

    final public function validateNonNullValue(mixed $rawValue) : bool
    {
        return \is_string($rawValue) && $this->options->offsetExists($rawValue);
    }

    final public function addDirective(
        EnumLocation $directive,
        array $arguments = [],
    ) : static
    {
        $this->directiveUsages[] = new DirectiveUsage($directive, $arguments);

        return $this;
    }

    private static function getItemDescription(\ReflectionClassConstant|\ReflectionEnumBackedCase $reflection) : ?string
    {
        $attrs = $reflection->getAttributes(Description::class);

        if (\count($attrs) === 1) {
            $attr = $attrs[0]->newInstance();

            return $attr->getValue();
        }

        $comment = $reflection->getDocComment();

        if ($comment) {
            return \trim($comment, '/* ');
        }

        return null;
    }
}
