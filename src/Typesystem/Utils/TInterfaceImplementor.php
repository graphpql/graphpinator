<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Utils;

/**
 * Trait TInterfaceImplementor which is implementation of InterfaceImplementor interface.
 */
trait TInterfaceImplementor
{
    protected ?\Graphpinator\Typesystem\Field\FieldSet $fields = null;
    protected \Graphpinator\Typesystem\InterfaceSet $implements;

    /**
     * Returns interfaces, which this type implements.
     */
    public function getInterfaces() : \Graphpinator\Typesystem\InterfaceSet
    {
        return $this->implements;
    }

    /**
     * Checks whether this type implements given interface.
     * @param \Graphpinator\Typesystem\InterfaceType $interface
     */
    public function implements(\Graphpinator\Typesystem\InterfaceType $interface) : bool
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
    abstract protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\FieldSet;

    /**
     * Method to validate contract defined by interfaces - whether fields and their type match.
     */
    protected function validateInterfaceContract() : void
    {
        foreach ($this->implements as $interface) {
            self::validateInvariance($interface->getDirectiveUsages(), $this->getDirectiveUsages());

            foreach ($interface->getFields() as $fieldContract) {
                if (!$this->getFields()->offsetExists($fieldContract->getName())) {
                    throw new \Graphpinator\Typesystem\Exception\InterfaceContractMissingField(
                        $this->getName(),
                        $interface->getName(),
                        $fieldContract->getName(),
                    );
                }

                $field = $this->getFields()->offsetGet($fieldContract->getName());

                if (!$fieldContract->getType()->isInstanceOf($field->getType())) {
                    throw new \Graphpinator\Typesystem\Exception\InterfaceContractFieldTypeMismatch(
                        $this->getName(),
                        $interface->getName(),
                        $fieldContract->getName(),
                    );
                }

                try {
                    self::validateCovariance($fieldContract->getDirectiveUsages(), $field->getDirectiveUsages());
                } catch (\Throwable) {
                    throw new \Graphpinator\Typesystem\Exception\FieldDirectiveNotCovariant(
                        $this->getName(),
                        $interface->getName(),
                        $fieldContract->getName(),
                    );
                }

                foreach ($fieldContract->getArguments() as $argumentContract) {
                    if (!$field->getArguments()->offsetExists($argumentContract->getName())) {
                        throw new \Graphpinator\Typesystem\Exception\InterfaceContractMissingArgument(
                            $this->getName(),
                            $interface->getName(),
                            $fieldContract->getName(),
                            $argumentContract->getName(),
                        );
                    }

                    $argument = $field->getArguments()->offsetGet($argumentContract->getName());

                    if (!$argument->getType()->isInstanceOf($argumentContract->getType())) {
                        throw new \Graphpinator\Typesystem\Exception\InterfaceContractArgumentTypeMismatch(
                            $this->getName(),
                            $interface->getName(),
                            $fieldContract->getName(),
                            $argumentContract->getName(),
                        );
                    }

                    try {
                        self::validateContravariance($argumentContract->getDirectiveUsages(), $argument->getDirectiveUsages());
                    } catch (\Throwable) {
                        throw new \Graphpinator\Typesystem\Exception\ArgumentDirectiveNotContravariant(
                            $this->getName(),
                            $interface->getName(),
                            $fieldContract->getName(),
                            $argumentContract->getName(),
                        );
                    }
                }

                if ($field->getArguments()->count() === $fieldContract->getArguments()->count()) {
                    continue;
                }

                foreach ($field->getArguments() as $argument) {
                    if (!$fieldContract->getArguments()->offsetExists($argument->getName()) && $argument->getDefaultValue() === null) {
                        throw new \Graphpinator\Typesystem\Exception\InterfaceContractNewArgumentWithoutDefault(
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

    private static function validateInvariance(
        \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet $parent,
        \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet $child,
    ) : void
    {
        foreach ($parent as $index => $usage) {
            if ($child->offsetExists($index) &&
                $usage->getDirective() instanceof ($child->offsetGet($index)->getDirective()) &&
                $usage->getArgumentValues()->isSame($child->offsetGet($index)->getArgumentValues())) {
                continue;
            }

            throw new \Graphpinator\Typesystem\Exception\InterfaceDirectivesNotPreserved();
        }
    }

    private static function validateCovariance(
        \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet $parent,
        \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet $child,
    ) : void
    {
        self::compareVariance($parent, $child);
    }

    private static function validateContravariance(
        \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet $parent,
        \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet $child,
    ) : void
    {
        self::compareVariance($child, $parent);
    }

    private static function compareVariance(
        \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet $biggerSet,
        \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet $smallerSet,
    ) : void
    {
        $childIndex = 0;

        foreach ($biggerSet as $usage) {
            $directive = $usage->getDirective();
            \assert($directive instanceof \Graphpinator\Typesystem\Location\FieldDefinitionLocation
                || $directive instanceof \Graphpinator\Typesystem\Location\ArgumentDefinitionLocation);

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
