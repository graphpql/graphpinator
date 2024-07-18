<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

use Graphpinator\Common\Path;
use Graphpinator\Exception\GraphpinatorBase;
use Graphpinator\Normalizer\Directive\Directive;
use Graphpinator\Normalizer\Directive\DirectiveSet;
use Graphpinator\Normalizer\Exception\DirectiveIncorrectLocation;
use Graphpinator\Normalizer\Exception\DirectiveIncorrectUsage;
use Graphpinator\Normalizer\Exception\DirectiveNotExecutable;
use Graphpinator\Normalizer\Exception\DuplicatedDirective;
use Graphpinator\Normalizer\Exception\InvalidFragmentType;
use Graphpinator\Normalizer\Exception\OperationNotSupported;
use Graphpinator\Normalizer\Exception\SelectionOnComposite;
use Graphpinator\Normalizer\Exception\TypeConditionOutputable;
use Graphpinator\Normalizer\Exception\UnknownArgument;
use Graphpinator\Normalizer\Exception\UnknownDirective;
use Graphpinator\Normalizer\Exception\UnknownFragment;
use Graphpinator\Normalizer\Exception\UnknownType;
use Graphpinator\Normalizer\Exception\VariableTypeInputable;
use Graphpinator\Normalizer\Operation\Operation;
use Graphpinator\Normalizer\Operation\OperationSet;
use Graphpinator\Normalizer\Selection\Field;
use Graphpinator\Normalizer\Selection\FragmentSpread;
use Graphpinator\Normalizer\Selection\InlineFragment;
use Graphpinator\Normalizer\Selection\Selection;
use Graphpinator\Normalizer\Selection\SelectionSet;
use Graphpinator\Normalizer\Variable\Variable;
use Graphpinator\Normalizer\Variable\VariableSet;
use Graphpinator\Parser\Directive\Directive as ParserDirective;
use Graphpinator\Parser\Directive\DirectiveSet as ParserDirectiveSet;
use Graphpinator\Parser\Field\Field as ParserField;
use Graphpinator\Parser\Field\FieldSet as ParserFieldSet;
use Graphpinator\Parser\Fragment\FragmentSet;
use Graphpinator\Parser\FragmentSpread\FragmentSpread as ParserFragmentSpread;
use Graphpinator\Parser\FragmentSpread\InlineFragmentSpread;
use Graphpinator\Parser\FragmentSpread\NamedFragmentSpread;
use Graphpinator\Parser\Operation\Operation as ParserOperation;
use Graphpinator\Parser\Operation\OperationSet as ParserOperationSet;
use Graphpinator\Parser\ParsedRequest;
use Graphpinator\Parser\TypeRef\ListTypeRef;
use Graphpinator\Parser\TypeRef\NamedTypeRef;
use Graphpinator\Parser\TypeRef\NotNullRef;
use Graphpinator\Parser\TypeRef\TypeRef;
use Graphpinator\Parser\Value\ArgumentValueSet as ParserArgumentValueSet;
use Graphpinator\Parser\Value\Value;
use Graphpinator\Parser\Variable\Variable as ParserVariable;
use Graphpinator\Parser\Variable\VariableSet as ParserVariableSet;
use Graphpinator\Tokenizer\TokenType;
use Graphpinator\Typesystem\Argument\ArgumentSet;
use Graphpinator\Typesystem\Contract\ExecutableDirective;
use Graphpinator\Typesystem\Contract\Inputable;
use Graphpinator\Typesystem\Contract\LeafType;
use Graphpinator\Typesystem\Contract\NamedType;
use Graphpinator\Typesystem\Contract\Type as TypesystemType;
use Graphpinator\Typesystem\Contract\TypeConditionable;
use Graphpinator\Typesystem\Directive as TypesystemDirective;
use Graphpinator\Typesystem\Field\Field as TypesystemField;
use Graphpinator\Typesystem\ListType;
use Graphpinator\Typesystem\Location\ExecutableDirectiveLocation;
use Graphpinator\Typesystem\Location\FieldLocation;
use Graphpinator\Typesystem\Location\VariableDefinitionLocation;
use Graphpinator\Typesystem\NotNullType;
use Graphpinator\Typesystem\Schema;
use Graphpinator\Typesystem\Type;
use Graphpinator\Typesystem\UnionType;
use Graphpinator\Value\ArgumentValue;
use Graphpinator\Value\ArgumentValueSet;
use Graphpinator\Value\ConvertParserValueVisitor;
use Graphpinator\Value\ConvertRawValueVisitor;

final class Normalizer
{
    private Path $path;
    private \SplStack $scopeStack;
    private FragmentSet $fragmentDefinitions;
    private VariableSet $variableSet;

    public function __construct(
        private Schema $schema,
    )
    {
    }

    public function normalize(ParsedRequest $parsedRequest) : NormalizedRequest
    {
        $fragmentCycleValidator = new FragmentCycleValidator($parsedRequest->getFragments());
        $fragmentCycleValidator->validate();

        $this->path = new Path();
        $this->scopeStack = new \SplStack();
        $this->fragmentDefinitions = $parsedRequest->getFragments();

        try {
            return new NormalizedRequest(
                $this->normalizeOperationSet($parsedRequest->getOperations()),
            );
        } catch (GraphpinatorBase $e) {
            throw $e->setPath($this->path);
        }
    }

    private static function typeCanOccur(NamedType $parentType, NamedType $typeCond) : bool
    {
        // union requires some special handling here to deduce match within all of his types
        $occurrenceAttempts = $parentType instanceof UnionType
            ? $parentType->getTypes()
            : [$parentType];

        foreach ($occurrenceAttempts as $type) {
            if ($typeCond->isInstanceOf($type) || $type->isInstanceOf($typeCond)) {
                return true;
            }
        }

        return false;
    }

    private function normalizeOperationSet(ParserOperationSet $operationSet,) : OperationSet
    {
        $normalized = [];

        foreach ($operationSet as $operation) {
            $this->path->add($operation->getName() . ' <operation>');
            $normalized[] = $this->normalizeOperation($operation);
            $this->path->pop();
        }

        return new OperationSet($normalized);
    }

    private function normalizeOperation(ParserOperation $operation) : Operation
    {
        $rootObject = match ($operation->getType()) {
            TokenType::QUERY->value => $this->schema->getQuery(),
            TokenType::MUTATION->value => $this->schema->getMutation(),
            TokenType::SUBSCRIPTION->value => $this->schema->getSubscription(),
        };

        if (!$rootObject instanceof Type) {
            throw new OperationNotSupported($operation->getType());
        }

        $this->scopeStack->push($rootObject);

        $this->variableSet = $this->normalizeVariables($operation->getVariables());
        $children = $this->normalizeFieldSet($operation->getFields());
        $directives = $this->normalizeDirectiveSet(
            $operation->getDirectives(),
            ExecutableDirectiveLocation::from(\strtoupper($operation->getType())),
        );

        $this->scopeStack->pop();

        return new Operation(
            $operation->getType(),
            $operation->getName(),
            $rootObject,
            $children,
            $this->variableSet,
            $directives,
        );
    }

    private function normalizeVariables(ParserVariableSet $variableSet) : VariableSet
    {
        $normalized = [];

        foreach ($variableSet as $variable) {
            $this->path->add($variable->getName() . ' <variable>');
            $normalized[] = $this->normalizeVariable($variable);
            $this->path->pop();
        }

        return new VariableSet($normalized);
    }

    private function normalizeVariable(ParserVariable $variable) : Variable
    {
        $type = $this->normalizeTypeRef($variable->getType());
        $defaultValue = $variable->getDefault();

        if (!$type->isInputable()) {
            throw new VariableTypeInputable($variable->getName());
        }

        \assert($type instanceof Inputable);

        $normalized = new Variable(
            $variable->getName(),
            $type,
            $defaultValue instanceof Value
                ? $defaultValue->accept(new ConvertParserValueVisitor($type, null, $this->path))
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

    private function normalizeFieldSet(ParserFieldSet $fieldSet) : SelectionSet
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
        $refiner = new SelectionSetRefiner($result);
        $refiner->refine();

        return $result;
    }

    private function normalizeField(ParserField $field) : Field
    {
        $parentType = $this->scopeStack->top();

        $fieldDef = $parentType->accept(new GetFieldVisitor($field->getName()));
        $fieldType = $fieldDef->getType()->getNamedType();

        $this->scopeStack->push($fieldType);

        $arguments = $this->normalizeArgumentValueSet($field->getArguments(), $fieldDef->getArguments());
        $directives = $field->getDirectives() instanceof ParserDirectiveSet
            ? $this->normalizeDirectiveSet($field->getDirectives(), ExecutableDirectiveLocation::FIELD, $fieldDef)
            : new DirectiveSet();
        $children = $field->getFields() instanceof ParserFieldSet
            ? $this->normalizeFieldSet($field->getFields())
            : null;

        if ($children === null && !$fieldType instanceof LeafType) {
            throw new SelectionOnComposite();
        }

        $this->scopeStack->pop();

        return new Field(
            $fieldDef,
            $field->getAlias()
                ?? $fieldDef->getName(),
            $arguments,
            $directives,
            $children,
        );
    }

    private function normalizeDirectiveSet(
        ParserDirectiveSet $directiveSet,
        ExecutableDirectiveLocation $location,
        TypesystemField|Variable|null $usage = null,
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

            if ($usage instanceof Variable) {
                \assert($directiveDef instanceof VariableDefinitionLocation);
                $directiveDef->validateVariableUsage($usage, $normalizedDirective->getArguments());
            }

            $normalized[] = $normalizedDirective;
            $this->path->pop();
        }

        return new DirectiveSet($normalized);
    }

    private function normalizeDirective(
        ParserDirective $directive,
        ExecutableDirectiveLocation $location,
        TypesystemField|Variable|null $usage = null,
    ) : Directive
    {
        $directiveDef = $this->schema->getContainer()->getDirective($directive->getName());

        if (!$directiveDef instanceof TypesystemDirective) {
            throw new UnknownDirective($directive->getName());
        }

        if (!$directiveDef instanceof ExecutableDirective) {
            throw new DirectiveNotExecutable($directive->getName());
        }

        if (!\in_array($location, $directiveDef->getLocations(), true)) {
            throw new DirectiveIncorrectLocation($directive->getName());
        }

        $arguments = $this->normalizeArgumentValueSet($directive->getArguments(), $directiveDef->getArguments());

        if ($location === ExecutableDirectiveLocation::FIELD) {
            \assert($directiveDef instanceof FieldLocation);

            if (!$directiveDef->validateFieldUsage($usage, $arguments)) {
                throw new DirectiveIncorrectUsage($directive->getName());
            }
        }

        return new Directive($directiveDef, $arguments);
    }

    private function normalizeArgumentValueSet(?ParserArgumentValueSet $argumentValueSet, ArgumentSet $argumentSet) : ArgumentValueSet
    {
        $argumentValueSet ??= new ParserArgumentValueSet();
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
                    new ConvertParserValueVisitor(
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
            $items[] = $default instanceof ArgumentValue
                ? $default
                : new ArgumentValue(
                    $argument,
                    $argument->getType()->accept(new ConvertRawValueVisitor(null, $this->path)),
                    false,
                ); // null is automatically passed to argument values, even if omitted
            $this->path->pop();
        }

        return new ArgumentValueSet($items);
    }

    private function normalizeFragmentSpread(ParserFragmentSpread $fragmentSpread) : Selection
    {
        return match ($fragmentSpread::class) {
            NamedFragmentSpread::class =>
                $this->normalizeNamedFragmentSpread($fragmentSpread),
            InlineFragmentSpread::class =>
                $this->normalizeInlineFragmentSpread($fragmentSpread),
            default =>
                throw new \LogicException(),
        };
    }

    private function normalizeNamedFragmentSpread(NamedFragmentSpread $fragmentSpread) : FragmentSpread
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

        return new FragmentSpread($fragmentSpread->getName(), $fields, $directives, $typeCond);
    }

    private function normalizeInlineFragmentSpread(InlineFragmentSpread $fragmentSpread) : InlineFragment
    {
        $this->path->add('<inline fragment>');

        $typeCond = $fragmentSpread->getTypeCond() instanceof NamedTypeRef
            ? $this->normalizeTypeRef($fragmentSpread->getTypeCond())
            : null;

        if ($typeCond instanceof NamedType) {
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

    private function normalizeTypeRef(TypeRef $typeRef) : TypesystemType
    {
        return match ($typeRef::class) {
            NamedTypeRef::class =>
                $this->schema->getContainer()->getType($typeRef->getName())
                    ?? throw new UnknownType($typeRef->getName()),
            ListTypeRef::class =>
                new ListType($this->normalizeTypeRef($typeRef->getInnerRef())),
            NotNullRef::class =>
                new NotNullType($this->normalizeTypeRef($typeRef->getInnerRef())),
            default =>
                throw new \LogicException(),
        };
    }

    private function validateTypeCondition(NamedType $typeCond) : void
    {
        if (!$typeCond instanceof TypeConditionable) {
            throw new TypeConditionOutputable();
        }

        $parentType = $this->scopeStack->top();
        \assert($parentType instanceof TypeConditionable);

        if (!self::typeCanOccur($parentType, $typeCond)) {
            throw new InvalidFragmentType($typeCond->getName(), $this->scopeStack->top()->getName());
        }
    }
}
