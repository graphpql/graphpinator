<?php

declare(strict_types = 1);

namespace Graphpinator\Resolver;

final class ResolveSelectionVisitor implements \Graphpinator\Normalizer\Selection\SelectionVisitor
{
    use \Nette\SmartObject;

    public function __construct(
        private \Graphpinator\Value\ResolvedValue $parentResult,
        private \stdClass $result,
    )
    {
    }

    public function visitField(\Graphpinator\Normalizer\Selection\Field $field) : mixed
    {
        $type = $this->parentResult->getType();
        \assert($type instanceof \Graphpinator\Typesystem\Type);

        foreach ($field->getDirectives() as $directive) {
            $directiveResult = $directive->getDirective()->resolveFieldBefore($directive->getArguments());

            if (self::shouldSkip($directiveResult)) {
                return null;
            }
        }

        if (\property_exists($this->result, $field->getOutputName())) {
            $fieldValue = $this->result->{$field->getOutputName()};
            \assert($fieldValue instanceof \Graphpinator\Value\FieldValue);

            if ($field->getSelections() instanceof \Graphpinator\Normalizer\Selection\SelectionSet) {
                $typeValue = $fieldValue->getValue();
                \assert($typeValue instanceof \Graphpinator\Value\TypeValue);
                $resolvedValue = $fieldValue->getIntermediateValue();
                $resolvedValue->getType()->accept(new ResolveVisitor($field->getSelections(), $resolvedValue, $typeValue->getRawValue()));
            }

            foreach ($field->getDirectives() as $directive) {
                $directive->getDirective()->resolveFieldAfter($directive->getArguments(), $fieldValue);
            }
        } else {
            $fieldDef = $type->getMetaFields()[$field->getName()]
                ?? $type->getFields()[$field->getName()];

            foreach ($fieldDef->getDirectiveUsages() as $directive) {
                $directive->getDirective()->resolveFieldDefinitionStart($directive->getArgumentValues(), $this->parentResult);
            }

            $arguments = $field->getArguments();

            foreach ($arguments as $argumentValue) {
                $argumentValue->resolveNonPureDirectives();
            }

            foreach ($fieldDef->getDirectiveUsages() as $directive) {
                $directive->getDirective()->resolveFieldDefinitionBefore($directive->getArgumentValues(), $this->parentResult, $arguments);
            }

            $rawArguments = $arguments->getValuesForResolver();
            \array_unshift($rawArguments, $this->parentResult->getRawValue());
            $rawValue = \call_user_func_array($fieldDef->getResolveFunction(), $rawArguments);
            $resolvedValue = $fieldDef->getType()->accept(new CreateResolvedValueVisitor($rawValue));

            if (!$resolvedValue->getType()->isInstanceOf($fieldDef->getType())) {
                throw new \Graphpinator\Resolver\Exception\FieldResultTypeMismatch();
            }

            foreach ($fieldDef->getDirectiveUsages() as $directive) {
                $directive->getDirective()->resolveFieldDefinitionAfter($directive->getArgumentValues(), $resolvedValue, $arguments);
            }

            $fieldValue = new \Graphpinator\Value\FieldValue($fieldDef, $resolvedValue instanceof \Graphpinator\Value\NullValue
                ? $resolvedValue
                : $resolvedValue->getType()->accept(new ResolveVisitor($field->getSelections(), $resolvedValue)), $resolvedValue);

            foreach ($field->getDirectives() as $directive) {
                $directiveResult = $directive->getDirective()->resolveFieldAfter($directive->getArguments(), $fieldValue);

                if (self::shouldSkip($directiveResult)) {
                    return null;
                }
            }

            $this->result->{$field->getOutputName()} = $fieldValue;
        }

        return null;
    }

    public function visitFragmentSpread(\Graphpinator\Normalizer\Selection\FragmentSpread $fragmentSpread) : mixed
    {
        if (!$this->parentResult->getType()->isInstanceOf($fragmentSpread->getTypeCondition())) {
            return null;
        }

        foreach ($fragmentSpread->getDirectives() as $directive) {
            $directiveResult = $directive->getDirective()->resolveFragmentSpreadBefore($directive->getArguments());

            if (self::shouldSkip($directiveResult)) {
                return null;
            }
        }

        foreach ($fragmentSpread->getSelections() as $selection) {
            $selection->accept(new ResolveSelectionVisitor($this->parentResult, $this->result));
        }

        foreach ($fragmentSpread->getDirectives() as $directive) {
            $directive->getDirective()->resolveFragmentSpreadAfter($directive->getArguments());

            // skip is not allowed here due to implementation complexity and rarity of use-cases
        }

        return null;
    }

    public function visitInlineFragment(\Graphpinator\Normalizer\Selection\InlineFragment $inlineFragment) : mixed
    {
        if ($inlineFragment->getTypeCondition() instanceof \Graphpinator\Typesystem\Contract\NamedType &&
            !$this->parentResult->getType()->isInstanceOf($inlineFragment->getTypeCondition())) {
            return null;
        }

        foreach ($inlineFragment->getDirectives() as $directive) {
            $directiveResult = $directive->getDirective()->resolveInlineFragmentBefore($directive->getArguments());

            if (self::shouldSkip($directiveResult)) {
                return null;
            }
        }

        foreach ($inlineFragment->getSelections() as $selection) {
            $selection->accept(new ResolveSelectionVisitor($this->parentResult, $this->result));
        }

        foreach ($inlineFragment->getDirectives() as $directive) {
            $directive->getDirective()->resolveInlineFragmentAfter($directive->getArguments());

            // skip is not allowed here due to implementation complexity and rarity of use-cases
        }

        return null;
    }

    private static function shouldSkip(string $directiveResult) : bool
    {
        if (\array_key_exists($directiveResult, \Graphpinator\Typesystem\Location\FieldLocation::ENUM)) {
            return $directiveResult === \Graphpinator\Typesystem\Location\FieldLocation::SKIP;
        }

        throw new \Graphpinator\Resolver\Exception\InvalidDirectiveResult();
    }
}
