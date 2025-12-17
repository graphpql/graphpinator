<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Utils;

use Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet;
use Graphpinator\Typesystem\Exception\ArgumentDirectiveNotContravariant;
use Graphpinator\Typesystem\Exception\FieldDirectiveNotCovariant;
use Graphpinator\Typesystem\Exception\InterfaceContractArgumentTypeMismatch;
use Graphpinator\Typesystem\Exception\InterfaceContractFieldTypeMismatch;
use Graphpinator\Typesystem\Exception\InterfaceContractMissingArgument;
use Graphpinator\Typesystem\Exception\InterfaceContractMissingField;
use Graphpinator\Typesystem\Exception\InterfaceContractNewArgumentWithoutDefault;
use Graphpinator\Typesystem\Exception\InterfaceDirectivesNotPreserved;
use Graphpinator\Typesystem\Exception\VarianceError;
use Graphpinator\Typesystem\Field\FieldSet;
use Graphpinator\Typesystem\InterfaceSet;
use Graphpinator\Typesystem\InterfaceType;
use Graphpinator\Typesystem\Location\ArgumentDefinitionLocation;
use Graphpinator\Typesystem\Location\FieldDefinitionLocation;
use Graphpinator\Typesystem\Visitor\IsInstanceOfVisitor;

/**
 * Trait TInterfaceImplementor which is implementation of InterfaceImplementor interface.
 */
trait TInterfaceImplementor
{
    protected ?FieldSet $fields = null;
    protected InterfaceSet $implements;

    /**
     * Returns interfaces, which this type implements.
     */
    public function getInterfaces() : InterfaceSet
    {
        return $this->implements;
    }

    /**
     * Checks whether this type implements given interface.
     * @param InterfaceType $interface
     */
    public function implements(InterfaceType $interface) : bool
    {
        foreach ($this->implements as $temp) {
            if ($temp::class === $interface::class || $temp->implements($interface)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Fields are lazy defined.
     * This is (apart from performance considerations) done because of a possible cyclic dependency across fields.
     * Fields are therefore defined by implementing this method, instead of passing FieldSet to constructor.
     */
    abstract protected function getFieldDefinition() : FieldSet;

    /**
     * Method to validate contract defined by interfaces - whether fields and their type match.
     */
    protected function validateInterfaceContract() : void
    {
        foreach ($this->implements as $interface) {
            self::validateInvariance($interface->getDirectiveUsages(), $this->getDirectiveUsages());

            foreach ($interface->getFields() as $fieldContract) {
                if (!$this->getFields()->offsetExists($fieldContract->getName())) {
                    throw new InterfaceContractMissingField(
                        $this->getName(),
                        $interface->getName(),
                        $fieldContract->getName(),
                    );
                }

                $field = $this->getFields()->offsetGet($fieldContract->getName());

                if (!$field->getType()->accept(new IsInstanceOfVisitor($fieldContract->getType()))) {
                    throw new InterfaceContractFieldTypeMismatch(
                        $this->getName(),
                        $interface->getName(),
                        $fieldContract->getName(),
                    );
                }

                try {
                    self::validateCovariance($fieldContract->getDirectiveUsages(), $field->getDirectiveUsages());
                } catch (\Throwable $e) {
                    throw new FieldDirectiveNotCovariant(
                        $this->getName(),
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
                            $this->getName(),
                            $interface->getName(),
                            $fieldContract->getName(),
                            $argumentContract->getName(),
                        );
                    }

                    $argument = $field->getArguments()->offsetGet($argumentContract->getName());

                    if (!$argumentContract->getType()->accept(new IsInstanceOfVisitor($argument->getType()))) {
                        throw new InterfaceContractArgumentTypeMismatch(
                            $this->getName(),
                            $interface->getName(),
                            $fieldContract->getName(),
                            $argumentContract->getName(),
                        );
                    }

                    try {
                        self::validateContravariance($argumentContract->getDirectiveUsages(), $argument->getDirectiveUsages());
                    } catch (\Throwable $e) {
                        throw new ArgumentDirectiveNotContravariant(
                            $this->getName(),
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
                            $this->getName(),
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
            \assert($directive instanceof FieldDefinitionLocation
                || $directive instanceof ArgumentDefinitionLocation);

            if ($smallerSet->offsetExists($childIndex) && $directive instanceof ($smallerSet->offsetGet($childIndex)->getDirective())) {
                $directive->validateVariance(
                    $usage->getArgumentValues(),
                    $smallerSet->offsetGet($childIndex)->getArgumentValues(),
                );
                ++$childIndex;

                continue;
            }

            $directive->validateVariance(
                $usage->getArgumentValues(),
                null,
            );
        }
    }
}
