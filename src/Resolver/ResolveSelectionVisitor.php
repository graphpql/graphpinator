<?php

declare(strict_types = 1);

namespace Graphpinator\Resolver;

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
use Graphpinator\Value\FieldValue;
use Graphpinator\Value\ListResolvedValue;
use Graphpinator\Value\NullValue;
use Graphpinator\Value\ResolvedValue;
use Graphpinator\Value\TypeValue;

final class ResolveSelectionVisitor implements SelectionVisitor
{
    public function __construct(
        private ResolvedValue $parentResult,
        private \stdClass $result,
    )
    {
    }

    public function visitField(Field $field) : mixed
    {
        $type = $this->parentResult->getType();
        \assert($type instanceof Type);

        foreach ($field->getDirectives() as $directive) {
            $directiveDef = $directive->getDirective();
            \assert($directiveDef instanceof FieldLocation);

            if (self::shouldSkip($directiveDef->resolveFieldBefore($directive->getArguments()))) {
                return null;
            }
        }

        if (\property_exists($this->result, $field->getOutputName())) {
            $fieldValue = $this->result->{$field->getOutputName()};
            \assert($fieldValue instanceof FieldValue);

            if ($field->getSelections() instanceof SelectionSet) {
                self::addToResultingSelection($fieldValue->getValue(), $field->getSelections());
            }

            foreach ($field->getDirectives() as $directiveUsage) {
                $directive = $directiveUsage->getDirective();
                \assert($directive instanceof FieldLocation);

                $directive->resolveFieldAfter($directiveUsage->getArguments(), $fieldValue);
            }
        } else {
            $fieldDef = $type->getMetaFields()[$field->getName()]
                ?? $type->getFields()[$field->getName()];

            foreach ($fieldDef->getDirectiveUsages() as $directiveUsage) {
                $directive = $directiveUsage->getDirective();
                \assert($directive instanceof FieldDefinitionLocation);

                $directive->resolveFieldDefinitionStart($directiveUsage->getArgumentValues(), $this->parentResult);
            }

            $arguments = $field->getArguments();

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

            if (!$resolvedValue->getType()->getShapingType()->isInstanceOf($fieldDef->getType()->getShapingType())) {
                throw new FieldResultTypeMismatch();
            }

            foreach ($fieldDef->getDirectiveUsages() as $directiveUsage) {
                $directive = $directiveUsage->getDirective();
                \assert($directive instanceof FieldDefinitionLocation);

                $directive->resolveFieldDefinitionAfter($directiveUsage->getArgumentValues(), $resolvedValue, $arguments);
            }

            $fieldValue = new FieldValue($fieldDef, $resolvedValue instanceof NullValue
                ? $resolvedValue
                : $resolvedValue->getType()->accept(new ResolveVisitor($field->getSelections(), $resolvedValue)));

            foreach ($field->getDirectives() as $directive) {
                $directiveDef = $directive->getDirective();
                \assert($directiveDef instanceof FieldLocation);

                if (self::shouldSkip($directiveDef->resolveFieldAfter($directive->getArguments(), $fieldValue))) {
                    return null;
                }
            }

            $this->result->{$field->getOutputName()} = $fieldValue;
        }

        return null;
    }

    public function visitFragmentSpread(FragmentSpread $fragmentSpread) : mixed
    {
        if (!$this->parentResult->getType()->isInstanceOf($fragmentSpread->getTypeCondition())) {
            return null;
        }

        foreach ($fragmentSpread->getDirectives() as $directive) {
            $directiveDef = $directive->getDirective();
            \assert($directiveDef instanceof FragmentSpreadLocation);

            if (self::shouldSkip($directiveDef->resolveFragmentSpreadBefore($directive->getArguments()))) {
                return null;
            }
        }

        foreach ($fragmentSpread->getSelections() as $selection) {
            $selection->accept(new ResolveSelectionVisitor($this->parentResult, $this->result));
        }

        foreach ($fragmentSpread->getDirectives() as $directive) {
            $directiveDef = $directive->getDirective();
            \assert($directiveDef instanceof FragmentSpreadLocation);

            $directiveDef->resolveFragmentSpreadAfter($directive->getArguments());
            // skip is not allowed here due to implementation complexity and rarity of use-cases
        }

        return null;
    }

    public function visitInlineFragment(InlineFragment $inlineFragment) : mixed
    {
        if ($inlineFragment->getTypeCondition() instanceof NamedType &&
            !$this->parentResult->getType()->isInstanceOf($inlineFragment->getTypeCondition())) {
            return null;
        }

        foreach ($inlineFragment->getDirectives() as $directive) {
            $directiveDef = $directive->getDirective();
            \assert($directiveDef instanceof InlineFragmentLocation);

            if (self::shouldSkip($directiveDef->resolveInlineFragmentBefore($directive->getArguments()))) {
                return null;
            }
        }

        foreach ($inlineFragment->getSelections() as $selection) {
            $selection->accept(new ResolveSelectionVisitor($this->parentResult, $this->result));
        }

        foreach ($inlineFragment->getDirectives() as $directive) {
            $directiveDef = $directive->getDirective();
            \assert($directiveDef instanceof InlineFragmentLocation);

            $directiveDef->resolveInlineFragmentAfter($directive->getArguments());
            // skip is not allowed here due to implementation complexity and rarity of use-cases
        }

        return null;
    }

    private static function shouldSkip(SelectionDirectiveResult $directiveResult) : bool
    {
        return $directiveResult === SelectionDirectiveResult::SKIP;
    }

    private static function addToResultingSelection(
        TypeValue|ListResolvedValue $value,
        SelectionSet $selectionSet,
    ) : void
    {
        if ($value instanceof TypeValue) {
            $resolvedValue = $value->getIntermediateValue();
            $resolvedValue->getType()->accept(new ResolveVisitor($selectionSet, $resolvedValue, $value->getRawValue()));

            return;
        }

        foreach ($value as $innerValue) {
            self::addToResultingSelection($innerValue, $selectionSet);
        }
    }
}
