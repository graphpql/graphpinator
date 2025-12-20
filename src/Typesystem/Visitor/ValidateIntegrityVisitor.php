<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Visitor;

use Graphpinator\Typesystem\Argument\Argument;
use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\Contract\ComponentVisitor;
use Graphpinator\Typesystem\Directive;
use Graphpinator\Typesystem\DirectiveUsage\DirectiveUsage;
use Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet;
use Graphpinator\Typesystem\EnumItem\EnumItem;
use Graphpinator\Typesystem\EnumType;
use Graphpinator\Typesystem\Exception\ArgumentDirectiveNotContravariant;
use Graphpinator\Typesystem\Exception\ArgumentInvalidTypeUsage;
use Graphpinator\Typesystem\Exception\DirectiveIncorrectType;
use Graphpinator\Typesystem\Exception\DuplicateNonRepeatableDirective;
use Graphpinator\Typesystem\Exception\EnumItemInvalid;
use Graphpinator\Typesystem\Exception\FieldDirectiveNotCovariant;
use Graphpinator\Typesystem\Exception\FieldInvalidTypeUsage;
use Graphpinator\Typesystem\Exception\FieldResolverNotIterable;
use Graphpinator\Typesystem\Exception\FieldResolverNullabilityMismatch;
use Graphpinator\Typesystem\Exception\FieldResolverVoidReturnType;
use Graphpinator\Typesystem\Exception\InputCycleDetected;
use Graphpinator\Typesystem\Exception\InputTypeMustDefineOneOreMoreFields;
use Graphpinator\Typesystem\Exception\InterfaceContractArgumentTypeMismatch;
use Graphpinator\Typesystem\Exception\InterfaceContractFieldTypeMismatch;
use Graphpinator\Typesystem\Exception\InterfaceContractMissingArgument;
use Graphpinator\Typesystem\Exception\InterfaceContractMissingField;
use Graphpinator\Typesystem\Exception\InterfaceContractNewArgumentWithoutDefault;
use Graphpinator\Typesystem\Exception\InterfaceCycleDetected;
use Graphpinator\Typesystem\Exception\InterfaceDirectivesNotPreserved;
use Graphpinator\Typesystem\Exception\InterfaceOrTypeMustDefineOneOrMoreFields;
use Graphpinator\Typesystem\Exception\RootOperationTypesMustBeDifferent;
use Graphpinator\Typesystem\Exception\RootOperationTypesMustBeWithinContainer;
use Graphpinator\Typesystem\Exception\UnionTypeMustDefineOneOrMoreTypes;
use Graphpinator\Typesystem\Exception\VarianceError;
use Graphpinator\Typesystem\Field\Field;
use Graphpinator\Typesystem\Field\ResolvableField;
use Graphpinator\Typesystem\InputType;
use Graphpinator\Typesystem\InterfaceType;
use Graphpinator\Typesystem\ListType;
use Graphpinator\Typesystem\Location\ArgumentDefinitionLocation;
use Graphpinator\Typesystem\Location\FieldDefinitionLocation;
use Graphpinator\Typesystem\Location\InputObjectLocation;
use Graphpinator\Typesystem\Location\ObjectLocation;
use Graphpinator\Typesystem\NotNullType;
use Graphpinator\Typesystem\ScalarType;
use Graphpinator\Typesystem\Schema;
use Graphpinator\Typesystem\Type;
use Graphpinator\Typesystem\UnionType;

/**
 * @implements ComponentVisitor<null>
 */
final readonly class ValidateIntegrityVisitor implements ComponentVisitor
{
    #[\Override]
    public function visitType(Type $type) : null
    {
        $fields = $type->getFields();

        if ($fields->count() === 0) {
            throw new InterfaceOrTypeMustDefineOneOrMoreFields();
        }

        foreach ($fields as $field) {
            $field->accept($this);
        }

        self::validateInterfaceContract($type);

        foreach ($type->getDirectiveUsages() as $usage) {
            $usage->accept($this);
            $directive = $usage->getDirective();

            if (!$directive instanceof ObjectLocation || !$directive->validateObjectUsage($type, $usage->getArgumentValues())) {
                throw new DirectiveIncorrectType();
            }
        }

        self::validateDirectiveRepeatability($type->getDirectiveUsages());

        return null;
    }

    #[\Override]
    public function visitInterface(InterfaceType $interface) : null
    {
        $fields = $interface->getFields();

        if ($fields->count() === 0) {
            throw new InterfaceOrTypeMustDefineOneOrMoreFields();
        }

        foreach ($fields as $field) {
            $field->accept($this);
        }

        self::validateInterfaceContract($interface);
        self::validateInterfaceCycles($interface);

        foreach ($interface->getDirectiveUsages() as $usage) {
            $usage->accept($this);
            $directive = $usage->getDirective();

            if (!$directive instanceof ObjectLocation || !$directive->validateObjectUsage($interface, $usage->getArgumentValues())) {
                throw new DirectiveIncorrectType();
            }
        }

        self::validateDirectiveRepeatability($interface->getDirectiveUsages());

        return null;
    }

    #[\Override]
    public function visitUnion(UnionType $union) : null
    {
        if ($union->getTypes()->count() === 0) {
            throw new UnionTypeMustDefineOneOrMoreTypes();
        }

        foreach ($union->getDirectiveUsages() as $usage) {
            $usage->accept($this);
        }

        self::validateDirectiveRepeatability($union->getDirectiveUsages());

        return null;
    }

    #[\Override]
    public function visitInput(InputType $input) : null
    {
        $arguments = $input->getArguments();

        if ($arguments->count() === 0) {
            throw new InputTypeMustDefineOneOreMoreFields();
        }

        foreach ($arguments as $argument) {
            $argument->accept($this);
        }

        self::validateInputCycles($input);

        foreach ($input->getDirectiveUsages() as $usage) {
            $usage->accept($this);
            $directive = $usage->getDirective();

            if (!$directive instanceof InputObjectLocation || !$directive->validateInputUsage($input, $usage->getArgumentValues())) {
                throw new DirectiveIncorrectType();
            }
        }

        self::validateDirectiveRepeatability($input->getDirectiveUsages());

        return null;
    }

    #[\Override]
    public function visitScalar(ScalarType $scalar) : null
    {
        foreach ($scalar->getDirectiveUsages() as $usage) {
            $usage->accept($this);
        }

        self::validateDirectiveRepeatability($scalar->getDirectiveUsages());

        return null;
    }

    #[\Override]
    public function visitEnum(EnumType $enum) : null
    {
        foreach ($enum->getItems() as $item) {
            $item->accept($this);
        }

        foreach ($enum->getDirectiveUsages() as $usage) {
            $usage->accept($this);
        }

        self::validateDirectiveRepeatability($enum->getDirectiveUsages());

        return null;
    }

    #[\Override]
    public function visitField(Field $field) : null
    {
        if (!$field->getType()->accept(new IsOutputableVisitor())) {
            throw new FieldInvalidTypeUsage($field->getName(), $field->getType()->accept(new PrintNameVisitor()));
        }

        foreach ($field->getArguments() as $argument) {
            $argument->accept($this);
        }

        if ($field instanceof ResolvableField) {
            self::validateFieldResolverFunction($field);
        }

        foreach ($field->getDirectiveUsages() as $usage) {
            $usage->accept($this);
            $directive = $usage->getDirective();

            if (!$directive instanceof FieldDefinitionLocation || !$directive->validateFieldUsage($field, $usage->getArgumentValues())) {
                throw new DirectiveIncorrectType();
            }
        }

        self::validateDirectiveRepeatability($field->getDirectiveUsages());

        return null;
    }

    #[\Override]
    public function visitArgument(Argument $argument) : null
    {
        if (!$argument->getType()->accept(new IsInputableVisitor())) {
            throw new ArgumentInvalidTypeUsage($argument->getName(), $argument->getType()->accept(new PrintNameVisitor()));
        }

        foreach ($argument->getDirectiveUsages() as $usage) {
            $usage->accept($this);
            $directive = $usage->getDirective();

            if (!$directive instanceof ArgumentDefinitionLocation || !$directive->validateArgumentUsage($argument, $usage->getArgumentValues())) {
                throw new DirectiveIncorrectType();
            }
        }

        self::validateDirectiveRepeatability($argument->getDirectiveUsages());

        return null;
    }

    #[\Override]
    public function visitDirectiveUsage(DirectiveUsage $directiveUsage) : null
    {
        return null;
    }

    #[\Override]
    public function visitEnumItem(EnumItem $enumItem) : null
    {
        $nameLexicallyInvalid = \preg_match('/^[a-zA-Z_]+\w*$/', $enumItem->getName()) !== 1; // @phpstan-ignore theCodingMachineSafe.function
        $nameKeyword = \in_array($enumItem->getName(), ['true', 'false', 'null'], true);

        if ($nameLexicallyInvalid || $nameKeyword) {
            throw new EnumItemInvalid($enumItem->getName());
        }

        foreach ($enumItem->getDirectiveUsages() as $usage) {
            $usage->accept($this);
        }

        self::validateDirectiveRepeatability($enumItem->getDirectiveUsages());

        return null;
    }

    #[\Override]
    public function visitSchema(Schema $schema) : null
    {
        $query = $schema->getQuery();
        $mutation = $schema->getMutation();
        $subscription = $schema->getSubscription();
        $container = $schema->getContainer();

        $this->validateContainer($container);

        if (self::isTypeSame($query, $mutation) ||
            self::isTypeSame($query, $subscription) ||
            self::isTypeSame($mutation, $subscription)) {
            throw new RootOperationTypesMustBeDifferent();
        }

        if ($container->getType($query->getName()) !== $query ||
            ($mutation instanceof Type && $container->getType($mutation->getName()) !== $mutation) ||
            ($subscription instanceof Type && $container->getType($subscription->getName()) !== $subscription)) {
            throw new RootOperationTypesMustBeWithinContainer();
        }

        foreach ($schema->getDirectiveUsages() as $usage) {
            $usage->accept($this);
        }

        self::validateDirectiveRepeatability($schema->getDirectiveUsages());

        return null;
    }

    #[\Override]
    public function visitDirective(Directive $directive) : null
    {
        return null;
    }

    /**
     * Method to validate contract defined by interfaces - whether fields and their type match.
     */
    private static function validateInterfaceContract(Type|InterfaceType $implementor) : void
    {
        foreach ($implementor->getInterfaces() as $interface) {
            self::validateInvariance($interface->getDirectiveUsages(), $implementor->getDirectiveUsages());

            foreach ($interface->getFields() as $fieldContract) {
                if (!$implementor->getFields()->offsetExists($fieldContract->getName())) {
                    throw new InterfaceContractMissingField(
                        $implementor->getName(),
                        $interface->getName(),
                        $fieldContract->getName(),
                    );
                }

                $field = $implementor->getFields()->offsetGet($fieldContract->getName());

                if (!$field->getType()->accept(new IsInstanceOfVisitor($fieldContract->getType()))) {
                    throw new InterfaceContractFieldTypeMismatch(
                        $implementor->getName(),
                        $interface->getName(),
                        $fieldContract->getName(),
                    );
                }

                try {
                    self::validateCovariance($fieldContract->getDirectiveUsages(), $field->getDirectiveUsages());
                } catch (\Throwable $e) {
                    throw new FieldDirectiveNotCovariant(
                        $implementor->getName(),
                        $interface->getName(),
                        $fieldContract->getName(),
                        $e instanceof VarianceError
                            ? $e->getExplanationMessage()
                            : 'No additional details were provided.',
                    );
                }

                foreach ($fieldContract->getArguments() as $argumentContract) {
                    if (!$field->getArguments()->offsetExists($argumentContract->getName())) {
                        throw new InterfaceContractMissingArgument(
                            $implementor->getName(),
                            $interface->getName(),
                            $fieldContract->getName(),
                            $argumentContract->getName(),
                        );
                    }

                    $argument = $field->getArguments()->offsetGet($argumentContract->getName());

                    if (!$argumentContract->getType()->accept(new IsInstanceOfVisitor($argument->getType()))) {
                        throw new InterfaceContractArgumentTypeMismatch(
                            $implementor->getName(),
                            $interface->getName(),
                            $fieldContract->getName(),
                            $argumentContract->getName(),
                        );
                    }

                    try {
                        self::validateContravariance($argumentContract->getDirectiveUsages(), $argument->getDirectiveUsages());
                    } catch (\Throwable $e) {
                        throw new ArgumentDirectiveNotContravariant(
                            $implementor->getName(),
                            $interface->getName(),
                            $fieldContract->getName(),
                            $argumentContract->getName(),
                            $e instanceof VarianceError
                                ? $e->getExplanationMessage()
                                : 'No additional details were provided.',
                        );
                    }
                }

                if ($field->getArguments()->count() === $fieldContract->getArguments()->count()) {
                    continue;
                }

                foreach ($field->getArguments() as $argument) {
                    if (!$fieldContract->getArguments()->offsetExists($argument->getName()) && $argument->getDefaultValue() === null) {
                        throw new InterfaceContractNewArgumentWithoutDefault(
                            $implementor->getName(),
                            $interface->getName(),
                            $field->getName(),
                            $argument->getName(),
                        );
                    }
                }
            }
        }
    }

    private static function validateInvariance(DirectiveUsageSet $parent, DirectiveUsageSet $child) : void
    {
        foreach ($parent as $index => $usage) {
            if ($child->offsetExists($index) &&
                $usage->getDirective() instanceof ($child->offsetGet($index)->getDirective()) &&
                $usage->getArgumentValues()->isSame($child->offsetGet($index)->getArgumentValues())) {
                continue;
            }

            throw new InterfaceDirectivesNotPreserved();
        }
    }

    private static function validateCovariance(DirectiveUsageSet $parent, DirectiveUsageSet $child) : void
    {
        self::compareVariance($parent, $child);
    }

    private static function validateContravariance(DirectiveUsageSet $parent, DirectiveUsageSet $child) : void
    {
        self::compareVariance($child, $parent);
    }

    private static function compareVariance(DirectiveUsageSet $biggerSet, DirectiveUsageSet $smallerSet) : void
    {
        $childIndex = 0;

        foreach ($biggerSet as $usage) {
            $directive = $usage->getDirective();
            \assert($directive instanceof FieldDefinitionLocation || $directive instanceof ArgumentDefinitionLocation);

            if ($smallerSet->offsetExists($childIndex) && $directive instanceof ($smallerSet->offsetGet($childIndex)->getDirective())) {
                $directive->validateVariance($usage->getArgumentValues(), $smallerSet->offsetGet($childIndex)->getArgumentValues());
                ++$childIndex;

                continue;
            }

            $directive->validateVariance($usage->getArgumentValues(), null);
        }
    }

    private static function isTypeSame(?Type $lhs, ?Type $rhs) : bool
    {
        return $lhs === $rhs
            && ($lhs !== null || $rhs !== null);
    }

    /**
     * @param InputType $input
     * @param array<string, true> $stack
     */
    private static function validateInputCycles(InputType $input, array $stack = []) : void
    {
        if (\array_key_exists($input->getName(), $stack)) {
            throw new InputCycleDetected(\array_keys($stack));
        }

        $stack[$input->getName()] = true;

        foreach ($input->getArguments() as $argumentContract) {
            $argumentType = $argumentContract->getType();

            if (!$argumentType instanceof NotNullType) {
                continue;
            }

            $argumentType = $argumentType->getInnerType();

            if (!$argumentType instanceof InputType) {
                continue;
            }

            self::validateInputCycles($argumentType, $stack);
        }

        unset($stack[$input->getName()]);
    }

    /**
     * @param InterfaceType $interface
     * @param array<string, true> $stack
     */
    private static function validateInterfaceCycles(InterfaceType $interface, array $stack = []) : void
    {
        if (\array_key_exists($interface->getName(), $stack)) {
            throw new InterfaceCycleDetected(\array_keys($stack));
        }

        $stack[$interface->getName()] = true;

        foreach ($interface->getInterfaces() as $implementedInterface) {
            self::validateInterfaceCycles($implementedInterface, $stack);
        }

        unset($stack[$interface->getName()]);
    }

    private static function validateDirectiveRepeatability(DirectiveUsageSet $directiveUsageSet) : void
    {
        $nonRepeatableDirectives = [];

        foreach ($directiveUsageSet as $directiveUsage) {
            $directive = $directiveUsage->getDirective();

            if (!$directive->isRepeatable() && \array_key_exists($directive->getName(), $nonRepeatableDirectives)) {
                throw new DuplicateNonRepeatableDirective();
            }

            $nonRepeatableDirectives[$directive->getName()] = true;
        }
    }

    private function validateContainer(Container $container) : void
    {
        foreach ($container->getTypes() as $type) {
            $type->accept($this);
        }

        foreach ($container->getDirectives() as $directive) {
            $directive->accept($this);
        }
    }

    private static function validateFieldResolverFunction(ResolvableField $field) : void
    {
        $functionReturnType = (new \ReflectionFunction($field->getResolveFunction()))->getReturnType();

        if (!$functionReturnType instanceof \ReflectionType) {
            return; // the return type is not present -> skip validation
        }

        if ($functionReturnType instanceof \ReflectionNamedType) {
            if ($functionReturnType->getName() === 'void') {
                throw new FieldResolverVoidReturnType($field->getName());
            }

            if ($functionReturnType->getName() === 'never') {
                return;
            }
        }

        $fieldType = $field->getType();
        $isFieldNotNull = $fieldType instanceof NotNullType;

        if ($functionReturnType->allowsNull() === $isFieldNotNull) {
            throw new FieldResolverNullabilityMismatch($field->getName());
        }

        $shapingType = $fieldType->accept(new GetShapingTypeVisitor());

        if ($shapingType instanceof ListType && !self::isReturnTypeIterable($functionReturnType)) {
            throw new FieldResolverNotIterable($field->getName());
        }
    }

    private static function isReturnTypeIterable(\ReflectionType $type) : bool
    {
        if ($type instanceof \ReflectionNamedType) {
            $typeName = $type->getName();

            return $typeName === 'array'
                || $typeName === 'iterable'
                || \is_a($typeName, \Traversable::class, true);
        }

        if ($type instanceof \ReflectionUnionType) {
            foreach ($type->getTypes() as $subType) {
                if ($subType instanceof \ReflectionNamedType && $subType->getName() === 'null') {
                    continue;
                }

                if (!self::isReturnTypeIterable($subType)) {
                    return false;
                }
            }

            return true;
        }

        if ($type instanceof \ReflectionIntersectionType) {
            foreach ($type->getTypes() as $subType) {
                if (self::isReturnTypeIterable($subType)) {
                    return true;
                }
            }

            return false;
        }

        return false;
    }
}
