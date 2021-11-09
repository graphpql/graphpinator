<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

use \Graphpinator\Normalizer\Directive\DirectiveSet;
use \Graphpinator\Normalizer\Exception\DirectiveIncorrectLocation;
use \Graphpinator\Normalizer\Exception\DirectiveIncorrectUsage;
use \Graphpinator\Normalizer\Exception\DirectiveNotExecutable;
use \Graphpinator\Normalizer\Exception\DuplicatedDirective;
use \Graphpinator\Normalizer\Exception\InvalidFragmentType;
use \Graphpinator\Normalizer\Exception\OperationNotSupported;
use \Graphpinator\Normalizer\Exception\SelectionOnComposite;
use \Graphpinator\Normalizer\Exception\TypeConditionOutputable;
use \Graphpinator\Normalizer\Exception\UnknownArgument;
use \Graphpinator\Normalizer\Exception\UnknownDirective;
use \Graphpinator\Normalizer\Exception\UnknownFragment;
use \Graphpinator\Normalizer\Exception\UnknownType;
use \Graphpinator\Normalizer\Exception\VariableTypeInputable;
use \Graphpinator\Normalizer\Operation\OperationSet;
use \Graphpinator\Normalizer\Selection\FragmentSpread as NFragmentSpread;
use \Graphpinator\Normalizer\Selection\InlineFragment;
use \Graphpinator\Normalizer\Selection\SelectionSet;
use \Graphpinator\Normalizer\Variable\Variable;
use \Graphpinator\Normalizer\Variable\VariableSet;
use \Graphpinator\Parser\FragmentSpread\FragmentSpread;
use \Graphpinator\Parser\FragmentSpread\InlineFragmentSpread;
use \Graphpinator\Parser\FragmentSpread\NamedFragmentSpread;
use \Graphpinator\Tokenizer\OperationType;
use \Graphpinator\Typesystem\Contract\ExecutableDirective;
use \Graphpinator\Typesystem\Contract\TypeConditionable;
use \Graphpinator\Typesystem\Location\ExecutableDirectiveLocation;
use \Graphpinator\Value\ArgumentValue;

final class Normalizer
{
    use \Nette\SmartObject;

    private \Graphpinator\Common\Path $path;
    private \SplStack $scopeStack;
    private \Graphpinator\Parser\Fragment\FragmentSet $fragmentDefinitions;
    private VariableSet $variableSet;

    public function __construct(
        private \Graphpinator\Typesystem\Schema $schema,
    )
    {
    }

    public function normalize(\Graphpinator\Parser\ParsedRequest $parsedRequest) : NormalizedRequest
    {
        $fragmentCycleValidator = new FragmentCycleValidator($parsedRequest->getFragments());
        $fragmentCycleValidator->validate();

        $this->path = new \Graphpinator\Common\Path();
        $this->scopeStack = new \SplStack();
        $this->fragmentDefinitions = $parsedRequest->getFragments();

        try {
            return new \Graphpinator\Normalizer\NormalizedRequest(
                $this->normalizeOperationSet($parsedRequest->getOperations()),
            );
        } catch (\Graphpinator\Exception\GraphpinatorBase $e) {
            throw $e->setPath($this->path);
        }
    }

    private function normalizeOperationSet(
        \Graphpinator\Parser\Operation\OperationSet $operationSet,
    ) : OperationSet
    {
        $normalized = [];

        foreach ($operationSet as $operation) {
            $this->path->add($operation->getName() . ' <operation>');
            $normalized[] = $this->normalizeOperation($operation);
            $this->path->pop();
        }

        return new OperationSet($normalized);
    }

    private function normalizeOperation(
        \Graphpinator\Parser\Operation\Operation $operation,
    ) : \Graphpinator\Normalizer\Operation\Operation
    {
        $rootObject = match ($operation->getType()) {
            OperationType::QUERY => $this->schema->getQuery(),
            \Graphpinator\Tokenizer\OperationType::MUTATION => $this->schema->getMutation(),
            \Graphpinator\Tokenizer\OperationType::SUBSCRIPTION => $this->schema->getSubscription(),
        };

        if (!$rootObject instanceof \Graphpinator\Typesystem\Type) {
            throw new OperationNotSupported($operation->getType());
        }

        $this->scopeStack->push($rootObject);

        $this->variableSet = $this->normalizeVariables($operation->getVariables());
        $children = $this->normalizeFieldSet($operation->getFields());
        $directives = $this->normalizeDirectiveSet($operation->getDirectives(), \strtoupper($operation->getType()));

        $this->scopeStack->pop();

        return new \Graphpinator\Normalizer\Operation\Operation(
            $operation->getType(),
            $operation->getName(),
            $rootObject,
            $children,
            $this->variableSet,
            $directives,
        );
    }

    private function normalizeVariables(
        \Graphpinator\Parser\Variable\VariableSet $variableSet,
    ) : \Graphpinator\Normalizer\Variable\VariableSet
    {
        $normalized = [];

        foreach ($variableSet as $variable) {
            $this->path->add($variable->getName() . ' <variable>');
            $normalized[] = $this->normalizeVariable($variable);
            $this->path->pop();
        }

        return new \Graphpinator\Normalizer\Variable\VariableSet($normalized);
    }

    private function normalizeVariable(
        \Graphpinator\Parser\Variable\Variable $variable,
    ) : Variable
    {
        $type = $this->normalizeTypeRef($variable->getType());
        $defaultValue = $variable->getDefault();

        if (!$type->isInputable()) {
            throw new VariableTypeInputable($variable->getName());
        }

        \assert($type instanceof \Graphpinator\Typesystem\Contract\Inputable);

        $normalized = new Variable(
            $variable->getName(),
            $type,
            $defaultValue instanceof \Graphpinator\Parser\Value\Value
                ? $defaultValue->accept(new \Graphpinator\Value\ConvertParserValueVisitor($type, null, $this->path))
                : null,
        );

        $normalized->setDirectives(
            $this->normalizeDirectiveSet(
                $variable->getDirectives(),
                ExecutableDirectiveLocation::VARIABLE_DEFINITION,
                $normalized,
            ),
        );

        return $normalized;
    }

    private function normalizeFieldSet(
        \Graphpinator\Parser\Field\FieldSet $fieldSet,
    ) : SelectionSet
    {
        $normalized = [];

        foreach ($fieldSet as $field) {
            $this->path->add($field->getName() . ' <field>');
            $normalized[] = $this->normalizeField($field);
            $this->path->pop();
        }

        foreach ($fieldSet->getFragmentSpreads() as $fragmentSpread) {
            $normalized[] = $this->normalizeFragmentSpread($fragmentSpread);
            $this->path->pop();
            $this->scopeStack->pop();
        }

        $result = new SelectionSet($normalized);
        $validator = new SelectionSetValidator($result);
        $validator->validate();
        $refiner = new \Graphpinator\Normalizer\SelectionSetRefiner($result);
        $refiner->refine();

        return $result;
    }

    private function normalizeField(
        \Graphpinator\Parser\Field\Field $field,
    ) : \Graphpinator\Normalizer\Selection\Field
    {
        $parentType = $this->scopeStack->top();

        $fieldDef = $parentType->accept(new GetFieldVisitor($field->getName()));
        $fieldType = $fieldDef->getType()->getNamedType();

        $this->scopeStack->push($fieldType);

        $arguments = $this->normalizeArgumentValueSet($field->getArguments(), $fieldDef->getArguments());
        $directives = $field->getDirectives() instanceof \Graphpinator\Parser\Directive\DirectiveSet
            ? $this->normalizeDirectiveSet($field->getDirectives(), ExecutableDirectiveLocation::FIELD, $fieldDef)
            : new DirectiveSet();
        $children = $field->getFields() instanceof \Graphpinator\Parser\Field\FieldSet
            ? $this->normalizeFieldSet($field->getFields())
            : null;

        if ($children === null && !$fieldType instanceof \Graphpinator\Typesystem\Contract\LeafType) {
            throw new SelectionOnComposite();
        }

        $this->scopeStack->pop();

        return new \Graphpinator\Normalizer\Selection\Field(
            $fieldDef,
            $field->getAlias()
                ?? $fieldDef->getName(),
            $arguments,
            $directives,
            $children,
        );
    }

    private function normalizeDirectiveSet(
        \Graphpinator\Parser\Directive\DirectiveSet $directiveSet,
        string $location,
        \Graphpinator\Typesystem\Field\Field|Variable|null $usage = null,
    ) : DirectiveSet
    {
        $normalized = [];
        $directiveTypes = [];

        foreach ($directiveSet as $directive) {
            $this->path->add($directive->getName() . ' <directive>');
            $normalizedDirective = $this->normalizeDirective($directive, $location, $usage);
            $directiveDef = $normalizedDirective->getDirective();

            if (!$directiveDef->isRepeatable()) {
                if (\array_key_exists($directiveDef->getName(), $directiveTypes)) {
                    throw new DuplicatedDirective($directiveDef->getName());
                }

                $directiveTypes[$directiveDef->getName()] = true;
            }

            $normalized[] = $normalizedDirective;
            $this->path->pop();
        }

        return new DirectiveSet($normalized);
    }

    private function normalizeDirective(
        \Graphpinator\Parser\Directive\Directive $directive,
        string $location,
        \Graphpinator\Typesystem\Field\Field|Variable|null $usage = null,
    ) : \Graphpinator\Normalizer\Directive\Directive
    {
        $directiveDef = $this->schema->getContainer()->getDirective($directive->getName());

        if (!$directiveDef instanceof \Graphpinator\Typesystem\Directive) {
            throw new UnknownDirective($directive->getName());
        }

        if (!$directiveDef instanceof ExecutableDirective) {
            throw new DirectiveNotExecutable($directive->getName());
        }

        if (!\in_array($location, $directiveDef->getLocations(), true)) {
            throw new DirectiveIncorrectLocation($directive->getName());
        }

        $arguments = $this->normalizeArgumentValueSet($directive->getArguments(), $directiveDef->getArguments());

        if ($location === ExecutableDirectiveLocation::FIELD &&
            !$directiveDef->validateFieldUsage($usage, $arguments)) {
            throw new DirectiveIncorrectUsage($directive->getName());
        }

        return new \Graphpinator\Normalizer\Directive\Directive($directiveDef, $arguments);
    }

    private function normalizeArgumentValueSet(
        ?\Graphpinator\Parser\Value\ArgumentValueSet $argumentValueSet,
        \Graphpinator\Typesystem\Argument\ArgumentSet $argumentSet,
    ) : \Graphpinator\Value\ArgumentValueSet
    {
        $argumentValueSet ??= new \Graphpinator\Parser\Value\ArgumentValueSet();
        $items = [];

        foreach ($argumentValueSet as $value) {
            if (!$argumentSet->offsetExists($value->getName())) {
                throw new UnknownArgument($value->getName());
            }
        }

        foreach ($argumentSet as $argument) {
            $this->path->add($argument->getName() . ' <argument>');

            if ($argumentValueSet->offsetExists($argument->getName())) {
                $result = $argumentValueSet->offsetGet($argument->getName())->getValue()->accept(
                    new \Graphpinator\Value\ConvertParserValueVisitor(
                        $argument->getType(),
                        $this->variableSet
                            ?? null,
                        $this->path,
                    ),
                );

                $items[] = new ArgumentValue($argument, $result, true);
                $this->path->pop();

                continue;
            }

            $default = $argument->getDefaultValue();
            $items[] = $default instanceof \Graphpinator\Value\ArgumentValue
                ? $default
                : new \Graphpinator\Value\ArgumentValue(
                    $argument,
                    $argument->getType()->accept(new \Graphpinator\Value\ConvertRawValueVisitor(null, $this->path)),
                    false,
                ); // null is automatically passed to argument values, even if omitted
            $this->path->pop();
        }

        return new \Graphpinator\Value\ArgumentValueSet($items);
    }

    private function normalizeFragmentSpread(
        FragmentSpread $fragmentSpread,
    ) : \Graphpinator\Normalizer\Selection\Selection
    {
        return match ($fragmentSpread::class) {
            NamedFragmentSpread::class =>
                $this->normalizeNamedFragmentSpread($fragmentSpread),
            InlineFragmentSpread::class =>
                $this->normalizeInlineFragmentSpread($fragmentSpread),
        };
    }

    private function normalizeNamedFragmentSpread(
        NamedFragmentSpread $fragmentSpread,
    ) : NFragmentSpread
    {
        $this->path->add($fragmentSpread->getName() . ' <fragment spread>');

        if (!$this->fragmentDefinitions->offsetExists($fragmentSpread->getName())) {
            throw new UnknownFragment($fragmentSpread->getName());
        }

        $fragment = $this->fragmentDefinitions->offsetGet($fragmentSpread->getName());
        $typeCond = $this->normalizeTypeRef($fragment->getTypeCond());

        $this->validateTypeCondition($typeCond);
        $this->scopeStack->push($typeCond);

        $fields = $this->normalizeFieldSet($fragment->getFields());
        $directives = $this->normalizeDirectiveSet(
            $fragmentSpread->getDirectives(),
            ExecutableDirectiveLocation::FRAGMENT_SPREAD,
        );

        return new NFragmentSpread($fragmentSpread->getName(), $fields, $directives, $typeCond);
    }

    private function normalizeInlineFragmentSpread(
        InlineFragmentSpread $fragmentSpread,
    ) : InlineFragment
    {
        $this->path->add('<inline fragment>');

        $typeCond = $fragmentSpread->getTypeCond() instanceof \Graphpinator\Parser\TypeRef\NamedTypeRef
            ? $this->normalizeTypeRef($fragmentSpread->getTypeCond())
            : null;

        if ($typeCond instanceof \Graphpinator\Typesystem\Contract\NamedType) {
            $this->validateTypeCondition($typeCond);
            $this->scopeStack->push($typeCond);
        } else {
            $this->scopeStack->push($this->scopeStack->top());
        }

        $fields = $this->normalizeFieldSet($fragmentSpread->getFields());
        $directives = $this->normalizeDirectiveSet(
            $fragmentSpread->getDirectives(),
            ExecutableDirectiveLocation::INLINE_FRAGMENT,
        );

        return new InlineFragment($fields, $directives, $typeCond);
    }

    private function normalizeTypeRef(
        \Graphpinator\Parser\TypeRef\TypeRef $typeRef,
    ) : \Graphpinator\Typesystem\Contract\Type
    {
        return match ($typeRef::class) {
            \Graphpinator\Parser\TypeRef\NamedTypeRef::class =>
                $this->schema->getContainer()->getType($typeRef->getName())
                    ?? throw new UnknownType($typeRef->getName()),
            \Graphpinator\Parser\TypeRef\ListTypeRef::class =>
                new \Graphpinator\Typesystem\ListType($this->normalizeTypeRef($typeRef->getInnerRef())),
            \Graphpinator\Parser\TypeRef\NotNullRef::class =>
                new \Graphpinator\Typesystem\NotNullType($this->normalizeTypeRef($typeRef->getInnerRef())),
        };
    }

    private function validateTypeCondition(\Graphpinator\Typesystem\Contract\NamedType $typeCond) : void
    {
        if (!$typeCond instanceof TypeConditionable) {
            throw new TypeConditionOutputable();
        }

        $parentType = $this->scopeStack->top();
        \assert($parentType instanceof TypeConditionable);

        if (!$typeCond->isInstanceOf($parentType) && !$parentType->isInstanceOf($typeCond)) {
            throw new InvalidFragmentType($typeCond->getName(), $this->scopeStack->top()->getName());
        }
    }
}
