<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

final class Normalizer
{
    use \Nette\SmartObject;

    private \Graphpinator\Common\Path $path;
    private \SplStack $scopeStack;
    private \Graphpinator\Parser\Fragment\FragmentSet $fragmentDefinitions;
    private \Graphpinator\Normalizer\Variable\VariableSet $variableSet;

    public function __construct(
        private \Graphpinator\Type\Schema $schema,
    ) {}

    public function normalize(\Graphpinator\Parser\ParsedRequest $parsedRequest) : NormalizedRequest
    {
        foreach ($parsedRequest->getFragments() as $fragment) {
            $fragment->validateCycles($parsedRequest->getFragments(), []);
        }

        $this->path = new \Graphpinator\Common\Path();
        $this->scopeStack = new \SplStack();
        $this->fragmentDefinitions = $parsedRequest->getFragments();

        return new \Graphpinator\Normalizer\NormalizedRequest(
            $this->normalizeOperationSet($parsedRequest->getOperations())
        );
    }

    private function normalizeOperationSet(
        \Graphpinator\Parser\Operation\OperationSet $operationSet,
    ) : \Graphpinator\Normalizer\Operation\OperationSet
    {
        $normalized = [];

        foreach ($operationSet as $operation) {
            $normalized[] = $this->normalizeOperation($operation);
        }

        return new \Graphpinator\Normalizer\Operation\OperationSet($normalized);
    }

    private function normalizeOperation(
        \Graphpinator\Parser\Operation\Operation $operation,
    ) : \Graphpinator\Normalizer\Operation\Operation
    {
        $operationType = match ($operation->getType()) {
            \Graphpinator\Tokenizer\OperationType::QUERY => $this->schema->getQuery(),
            \Graphpinator\Tokenizer\OperationType::MUTATION => $this->schema->getMutation(),
            \Graphpinator\Tokenizer\OperationType::SUBSCRIPTION => $this->schema->getSubscription(),
        };

        if (!$operationType instanceof \Graphpinator\Type\Type) {
            throw new \Graphpinator\Exception\Normalizer\OperationNotSupported($operation->getType());
        }

        $this->path->add($operation->getName() . ' <' . $operation->getType() . '>');
        $this->scopeStack->push($operationType);

        $this->variableSet = $this->normalizeVariables($operation->getVariables());
        $children = $this->normalizeFieldSet($operation->getFields());
        $directives = $this->normalizeDirectiveSet($operation->getDirectives());
        $args = [$operationType, $children, $this->variableSet, $directives, $operation->getName()];

        $this->path->pop();
        $this->scopeStack->pop();

        return match ($operation->getType()) {
            \Graphpinator\Tokenizer\OperationType::QUERY => new \Graphpinator\Normalizer\Operation\Query(...$args),
            \Graphpinator\Tokenizer\OperationType::MUTATION => new \Graphpinator\Normalizer\Operation\Mutation(...$args),
            \Graphpinator\Tokenizer\OperationType::SUBSCRIPTION => new \Graphpinator\Normalizer\Operation\Subscription(...$args),
        };
    }

    private function normalizeVariables(
        \Graphpinator\Parser\Variable\VariableSet $variableSet,
    ) : \Graphpinator\Normalizer\Variable\VariableSet
    {
        $normalized = [];

        foreach ($variableSet as $variable) {
            $normalized[] = $this->normalizeVariable($variable);
        }

        return new \Graphpinator\Normalizer\Variable\VariableSet($normalized);
    }

    private function normalizeVariable(
        \Graphpinator\Parser\Variable\Variable $variable,
    ) : \Graphpinator\Normalizer\Variable\Variable
    {
        $type = $this->normalizeTypeRef($variable->getType());

        if (!$type->isInputable()) {
            throw new \Graphpinator\Exception\Normalizer\VariableTypeInputable();
        }

        \assert($type instanceof \Graphpinator\Type\Contract\Inputable);

        return new \Graphpinator\Normalizer\Variable\Variable(
            $variable->getName(),
            $type,
            $variable->getDefault() instanceof \Graphpinator\Parser\Value\Value
                ? $type->createInputedValue($variable->getDefault()->getRawValue())
                : null,
        );
    }

    private function normalizeFieldSet(
        \Graphpinator\Parser\Field\FieldSet $fieldSet,
    ) : \Graphpinator\Normalizer\Field\FieldSet
    {
        $normalized = [];

        foreach ($fieldSet as $field) {
            $normalized[] = $this->normalizeField($field);
        }

        $return = new \Graphpinator\Normalizer\Field\FieldSet($normalized);

        foreach ($this->normalizeFragmentSpreadSet($fieldSet->getFragmentSpreads()) as $fragmentSpread) {
            $return->mergeFieldSet($this->scopeStack->top(), $fragmentSpread->getFields());
        }

        return $return;
    }

    private function normalizeField(
        \Graphpinator\Parser\Field\Field $field,
    ) : \Graphpinator\Normalizer\Field\Field
    {
        $parentType = $this->scopeStack->top();
        \assert($parentType instanceof \Graphpinator\Type\Contract\Scopable);

        $fieldDef = $parentType->getField($field->getName());
        $fieldType = $fieldDef->getType()->getNamedType();

        $this->path->add($field->getName() . ' <field>');
        $this->scopeStack->push($fieldType);

        $arguments = $this->normalizeArgumentValueSet($field->getArguments(), $fieldDef->getArguments());
        $directives = $field->getDirectives() instanceof \Graphpinator\Parser\Directive\DirectiveSet
            ? $this->normalizeDirectiveSet($field->getDirectives(), $fieldDef)
            : new \Graphpinator\Normalizer\Directive\DirectiveSet();
        $children = $field->getFields() instanceof \Graphpinator\Parser\Field\FieldSet
            ? $this->normalizeFieldSet($field->getFields())
            : null;

        if ($children === null && !$fieldType instanceof \Graphpinator\Type\Contract\LeafDefinition) {
            throw new \Graphpinator\Exception\Normalizer\SelectionOnComposite();
        }

        $this->path->pop();
        $this->scopeStack->pop();

        return new \Graphpinator\Normalizer\Field\Field(
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
        \Graphpinator\Field\Field|null $usage = null,
    ) : \Graphpinator\Normalizer\Directive\DirectiveSet
    {
        $normalized = [];
        $directiveTypes = [];

        foreach ($directiveSet as $directive) {
            $normalizedDirective = $this->normalizeDirective($directive, $usage);
            $directiveDef = $normalizedDirective->getDirective();

            if (!$directiveDef->isRepeatable()) {
                if (\array_key_exists($directiveDef->getName(), $directiveTypes)) {
                    throw new \Graphpinator\Exception\Normalizer\DuplicatedDirective();
                }

                $directiveTypes[$directiveDef->getName()] = true;
            }

            $normalized[] = $normalizedDirective;
        }

        return new \Graphpinator\Normalizer\Directive\DirectiveSet($normalized);
    }

    private function normalizeDirective(
        \Graphpinator\Parser\Directive\Directive $directive,
        \Graphpinator\Field\Field|null $usage,
    ) : \Graphpinator\Normalizer\Directive\Directive
    {
        $directiveDef = $this->schema->getContainer()->getDirective($directive->getName());

        if (!$directiveDef instanceof \Graphpinator\Directive\Directive) {
            throw new \Graphpinator\Exception\Normalizer\UnknownDirective($directive->getName());
        }

        $this->path->add($directiveDef->getName() . ' <directive>');

        if (!$directiveDef instanceof \Graphpinator\Directive\Contract\ExecutableDefinition) {
            throw new \Graphpinator\Exception\Normalizer\DirectiveNotExecutable();
        }

        if (!\in_array($directive->getLocation(), $directiveDef->getLocations(), true)) {
            throw new \Graphpinator\Exception\Normalizer\DirectiveIncorrectLocation();
        }

        $arguments = $this->normalizeArgumentValueSet($directive->getArguments(), $directiveDef->getArguments());
        $usageIsValid = match ($directive->getLocation()) {
            \Graphpinator\Directive\ExecutableDirectiveLocation::FIELD,
            \Graphpinator\Directive\ExecutableDirectiveLocation::INLINE_FRAGMENT,
            \Graphpinator\Directive\ExecutableDirectiveLocation::FRAGMENT_SPREAD =>
                $directiveDef->validateFieldUsage($usage, $arguments),
            default => true,
        };

        if (!$usageIsValid) {
            throw new \Graphpinator\Exception\Normalizer\DirectiveIncorrectType();
        }

        $this->path->pop();

        return new \Graphpinator\Normalizer\Directive\Directive($directiveDef, $arguments);
    }

    private function normalizeArgumentValueSet(
        ?\Graphpinator\Parser\Value\ArgumentValueSet $argumentValueSet,
        \Graphpinator\Argument\ArgumentSet $argumentDef,
    ) : \Graphpinator\Value\ArgumentValueSet
    {
        try {
            return \Graphpinator\Value\ArgumentValueSet::fromParsed(
                $argumentValueSet
                    ?? new \Graphpinator\Parser\Value\ArgumentValueSet([]),
                $argumentDef,
                $this->variableSet,
            );
        } catch (\Graphpinator\Exception\GraphpinatorBase $e) {
            $e->setPath($this->path);

            throw $e;
        }
    }

    private function normalizeFragmentSpreadSet(
        \Graphpinator\Parser\FragmentSpread\FragmentSpreadSet $fragmentSpreadSet,
    ) : \Graphpinator\Normalizer\FragmentSpread\FragmentSpreadSet
    {
        $normalized = [];

        foreach ($fragmentSpreadSet as $fragmentSpread) {
            $normalized[] = $this->normalizeFragmentSpread($fragmentSpread);
        }

        return new \Graphpinator\Normalizer\FragmentSpread\FragmentSpreadSet($normalized);
    }

    private function normalizeFragmentSpread(
        \Graphpinator\Parser\FragmentSpread\FragmentSpread $fragmentSpread,
    ) : \Graphpinator\Normalizer\FragmentSpread\FragmentSpread
    {
        return match($fragmentSpread::class) {
            \Graphpinator\Parser\FragmentSpread\NamedFragmentSpread::class =>
                $this->normalizeNamedFragmentSpread($fragmentSpread),
            \Graphpinator\Parser\FragmentSpread\InlineFragmentSpread::class =>
                $this->normalizeInlineFragmentSpread($fragmentSpread),
        };
    }

    private function normalizeNamedFragmentSpread(
        \Graphpinator\Parser\FragmentSpread\NamedFragmentSpread $fragmentSpread,
    ) : \Graphpinator\Normalizer\FragmentSpread\FragmentSpread
    {
        if (!$this->fragmentDefinitions->offsetExists($fragmentSpread->getName())) {
            throw new \Graphpinator\Exception\Normalizer\UnknownFragment($fragmentSpread->getName());
        }

        $fragment = $this->fragmentDefinitions->offsetGet($fragmentSpread->getName());
        $typeCond = $this->normalizeTypeRef($fragment->getTypeCond());

        if (!$typeCond instanceof \Graphpinator\Type\Contract\TypeConditionable) {
            throw new \Graphpinator\Exception\Normalizer\TypeConditionOutputable();
        }

        $this->path->add($fragment->getName() . ' <fragment>');
        $this->scopeStack->push($typeCond);

        $fields = $this->normalizeFieldSet($fragment->getFields());

        foreach ($fields as $field) {
            $directives = $this->normalizeDirectiveSet($fragmentSpread->getDirectives(), $field->getField());

            $field->getDirectives()->merge($directives);
            $field->applyFragmentTypeCondition($typeCond);
        }

        $this->path->pop();
        $this->scopeStack->pop();

        return new \Graphpinator\Normalizer\FragmentSpread\FragmentSpread($fields);
    }

    private function normalizeInlineFragmentSpread(
        \Graphpinator\Parser\FragmentSpread\InlineFragmentSpread $fragmentSpread,
    ) : \Graphpinator\Normalizer\FragmentSpread\FragmentSpread
    {
        $typeCond = $fragmentSpread->getTypeCond() instanceof \Graphpinator\Parser\TypeRef\NamedTypeRef
            ? $this->normalizeTypeRef($fragmentSpread->getTypeCond())
            : null;

        if ($typeCond instanceof \Graphpinator\Type\Contract\NamedDefinition &&
            !$typeCond instanceof \Graphpinator\Type\Contract\TypeConditionable) {
            throw new \Graphpinator\Exception\Normalizer\TypeConditionOutputable();
        }

        $this->path->add('<inline fragment>');
        $this->scopeStack->push($typeCond
            ?? $this->scopeStack->top());

        $fields = $this->normalizeFieldSet($fragmentSpread->getFields());

        foreach ($fields as $field) {
            $directives = $this->normalizeDirectiveSet($fragmentSpread->getDirectives(), $field->getField());

            $field->getDirectives()->merge($directives);
            $field->applyFragmentTypeCondition($typeCond);
        }

        $this->path->pop();
        $this->scopeStack->pop();

        return new \Graphpinator\Normalizer\FragmentSpread\FragmentSpread($fields);
    }

    private function normalizeTypeRef(
        \Graphpinator\Parser\TypeRef\TypeRef $typeRef,
    ) : \Graphpinator\Type\Contract\Definition
    {
        return match ($typeRef::class) {
            \Graphpinator\Parser\TypeRef\NamedTypeRef::class =>
                $this->schema->getContainer()->getType($typeRef->getName())
                    ?? throw new \Graphpinator\Exception\Normalizer\UnknownType($typeRef->getName()),
            \Graphpinator\Parser\TypeRef\ListTypeRef::class =>
                new \Graphpinator\Type\ListType($this->normalizeTypeRef($typeRef->getInnerRef())),
            \Graphpinator\Parser\TypeRef\NotNullRef::class =>
                new \Graphpinator\Type\NotNullType($this->normalizeTypeRef($typeRef->getInnerRef())),
        };
    }
}
