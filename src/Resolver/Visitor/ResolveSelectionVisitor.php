<?php

declare(strict_types = 1);

namespace Graphpinator\Resolver\Visitor;

use Graphpinator\Normalizer\Selection\Field;
use Graphpinator\Normalizer\Selection\FragmentSpread;
use Graphpinator\Normalizer\Selection\InlineFragment;
use Graphpinator\Normalizer\Selection\SelectionSet;
use Graphpinator\Normalizer\Selection\SelectionVisitor;
use Graphpinator\Resolver\Exception\FieldResultTypeMismatch;
use Graphpinator\Typesystem\Contract\NamedType;
use Graphpinator\Typesystem\Location\FieldDefinitionLocation;
use Graphpinator\Typesystem\Location\FieldLocation;
use Graphpinator\Typesystem\Location\FragmentSpreadLocation;
use Graphpinator\Typesystem\Location\InlineFragmentLocation;
use Graphpinator\Typesystem\Location\SelectionDirectiveResult;
use Graphpinator\Typesystem\Type;
use Graphpinator\Typesystem\Visitor\GetShapingTypeVisitor;
use Graphpinator\Typesystem\Visitor\IsInstanceOfVisitor;
use Graphpinator\Value\Contract\Value;
use Graphpinator\Value\FieldValue;
use Graphpinator\Value\ListValue;
use Graphpinator\Value\NullValue;
use Graphpinator\Value\TypeValue;
use Graphpinator\Value\Visitor\CreateResolvedValueVisitor;

final class ResolveSelectionVisitor implements SelectionVisitor
{
    public function __construct(
        private Value $parentResult,
        private \stdClass $result,
    )
    {
    }

    #[\Override]
    public function visitField(Field $field) : null
    {
        $type = $this->parentResult->getType();
        \assert($type instanceof Type);

        foreach ($field->directives as $directive) {
            $directiveDef = $directive->directive;
            \assert($directiveDef instanceof FieldLocation);

            if (self::shouldSkip($directiveDef->resolveFieldBefore($directive->arguments))) {
                return null;
            }
        }

        if (\property_exists($this->result, $field->outputName)) {
            $fieldValue = $this->result->{$field->outputName};
            \assert($fieldValue instanceof FieldValue);

            if ($field->children instanceof SelectionSet && $fieldValue->value instanceof TypeValue) {
                self::addToResultingSelection($fieldValue->value, $field->children);
            }

            foreach ($field->directives as $directiveUsage) {
                $directive = $directiveUsage->directive;
                \assert($directive instanceof FieldLocation);

                $directive->resolveFieldAfter($directiveUsage->arguments, $fieldValue);
            }
        } else {
            $fieldDef = $type->getMetaFields()[$field->getName()]
                ?? $type->getFields()[$field->getName()];

            foreach ($fieldDef->getDirectiveUsages() as $directiveUsage) {
                $directive = $directiveUsage->getDirective();
                \assert($directive instanceof FieldDefinitionLocation);

                $directive->resolveFieldDefinitionStart($directiveUsage->getArgumentValues(), $this->parentResult);
            }

            $arguments = $field->arguments;

            foreach ($arguments as $argumentValue) {
                $argumentValue->resolveNonPureDirectives();
            }

            foreach ($fieldDef->getDirectiveUsages() as $directiveUsage) {
                $directive = $directiveUsage->getDirective();
                \assert($directive instanceof FieldDefinitionLocation);

                $directive->resolveFieldDefinitionBefore($directiveUsage->getArgumentValues(), $this->parentResult, $arguments);
            }

            $rawArguments = $arguments->getValuesForResolver();
            \array_unshift($rawArguments, $this->parentResult->getRawValue());
            $rawValue = \call_user_func_array($fieldDef->getResolveFunction(), $rawArguments);
            $resolvedValue = $fieldDef->getType()->accept(new CreateResolvedValueVisitor($rawValue));

            $resolvedShape = $resolvedValue->getType()->accept(new GetShapingTypeVisitor());
            $definitionShape = $fieldDef->getType()->accept(new GetShapingTypeVisitor());

            if (!$resolvedShape->accept(new IsInstanceOfVisitor($definitionShape))) {
                throw new FieldResultTypeMismatch();
            }

            foreach ($fieldDef->getDirectiveUsages() as $directiveUsage) {
                $directive = $directiveUsage->getDirective();
                \assert($directive instanceof FieldDefinitionLocation);

                $directive->resolveFieldDefinitionAfter($directiveUsage->getArgumentValues(), $resolvedValue, $arguments);
            }

            $fieldValue = new FieldValue($fieldDef, $resolvedValue instanceof NullValue
                ? $resolvedValue
                : $resolvedValue->getType()->accept(new ResolveVisitor($field->children, $resolvedValue)));

            foreach ($field->directives as $directive) {
                $directiveDef = $directive->directive;
                \assert($directiveDef instanceof FieldLocation);

                if (self::shouldSkip($directiveDef->resolveFieldAfter($directive->arguments, $fieldValue))) {
                    return null;
                }
            }

            $this->result->{$field->outputName} = $fieldValue;
        }

        return null;
    }

    #[\Override]
    public function visitFragmentSpread(FragmentSpread $fragmentSpread) : null
    {
        if (!$this->parentResult->getType()->accept(new IsInstanceOfVisitor($fragmentSpread->typeCondition))) {
            return null;
        }

        foreach ($fragmentSpread->directives as $directive) {
            $directiveDef = $directive->directive;
            \assert($directiveDef instanceof FragmentSpreadLocation);

            if (self::shouldSkip($directiveDef->resolveFragmentSpreadBefore($directive->arguments))) {
                return null;
            }
        }

        foreach ($fragmentSpread->children as $selection) {
            $selection->accept(new ResolveSelectionVisitor($this->parentResult, $this->result));
        }

        foreach ($fragmentSpread->directives as $directive) {
            $directiveDef = $directive->directive;
            \assert($directiveDef instanceof FragmentSpreadLocation);

            $directiveDef->resolveFragmentSpreadAfter($directive->arguments);
            // skip is not allowed here due to implementation complexity and rarity of use-cases
        }

        return null;
    }

    #[\Override]
    public function visitInlineFragment(InlineFragment $inlineFragment) : null
    {
        if ($inlineFragment->typeCondition instanceof NamedType &&
            !$this->parentResult->getType()->accept(new IsInstanceOfVisitor($inlineFragment->typeCondition))) {
            return null;
        }

        foreach ($inlineFragment->directives as $directive) {
            $directiveDef = $directive->directive;
            \assert($directiveDef instanceof InlineFragmentLocation);

            if (self::shouldSkip($directiveDef->resolveInlineFragmentBefore($directive->arguments))) {
                return null;
            }
        }

        foreach ($inlineFragment->children as $selection) {
            $selection->accept(new ResolveSelectionVisitor($this->parentResult, $this->result));
        }

        foreach ($inlineFragment->directives as $directive) {
            $directiveDef = $directive->directive;
            \assert($directiveDef instanceof InlineFragmentLocation);

            $directiveDef->resolveInlineFragmentAfter($directive->arguments);
            // skip is not allowed here due to implementation complexity and rarity of use-cases
        }

        return null;
    }

    private static function shouldSkip(SelectionDirectiveResult $directiveResult) : bool
    {
        return $directiveResult === SelectionDirectiveResult::SKIP;
    }

    private static function addToResultingSelection(TypeValue|ListValue|NullValue $value, SelectionSet $selectionSet) : void
    {
        if ($value instanceof NullValue) {
            return;
        }

        if ($value instanceof TypeValue) {
            $resolvedValue = $value->intermediateValue;
            $resolvedValue->getType()->accept(new ResolveVisitor($selectionSet, $resolvedValue, $value->getRawValue()));

            return;
        }

        foreach ($value as $innerValue) {
            \assert($innerValue instanceof TypeValue || $innerValue instanceof ListValue || $innerValue instanceof NullValue);
            self::addToResultingSelection($innerValue, $selectionSet);
        }
    }
}
