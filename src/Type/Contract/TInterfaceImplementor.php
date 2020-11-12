<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Contract;

/**
 * Trait TInterfaceImplementor which is implementation of InterfaceImplementor interface.
 */
trait TInterfaceImplementor
{
    protected ?\Graphpinator\Field\FieldSet $fields = null;
    protected \Graphpinator\Utils\InterfaceSet $implements;

    /**
     * Returns interfaces, which this type implements.
     */
    public function getInterfaces() : \Graphpinator\Utils\InterfaceSet
    {
        return $this->implements;
    }

    /**
     * Checks whether this type implements given interface.
     * @param \Graphpinator\Type\InterfaceType $interface
     */
    public function implements(\Graphpinator\Type\InterfaceType $interface) : bool
    {
        foreach ($this->implements as $temp) {
            if (\get_class($temp) === \get_class($interface) || $temp->implements($interface)) {
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
    abstract protected function getFieldDefinition() : \Graphpinator\Field\FieldSet;

    /**
     * Method to validate contract defined by interfaces - whether fields and their type match.
     */
    protected function validateInterfaces() : void
    {
        foreach ($this->implements as $interface) {
            if (!$interface->getConstraints()->validateObjectConstraint($this->getConstraints())) {
                throw new \Graphpinator\Exception\Type\ObjectConstraintsNotEqual();
            }

            foreach ($interface->getFields() as $fieldContract) {
                if (!$this->getFields()->offsetExists($fieldContract->getName())) {
                    throw new \Graphpinator\Exception\Type\InterfaceContractMissingField();
                }

                $field = $this->getFields()->offsetGet($fieldContract->getName());

                if (!$fieldContract->getType()->isInstanceOf($field->getType())) {
                    throw new \Graphpinator\Exception\Type\InterfaceContractFieldTypeMismatch();
                }

                if (!$field->getConstraints()->isContravariant($fieldContract->getConstraints())) {
                    throw new \Graphpinator\Exception\Type\FieldConstraintNotContravariant();
                }

                foreach ($fieldContract->getArguments() as $argumentContract) {
                    if (!$field->getArguments()->offsetExists($argumentContract->getName())) {
                        throw new \Graphpinator\Exception\Type\InterfaceContractMissingArgument();
                    }

                    $argument = $field->getArguments()->offsetGet($argumentContract->getName());

                    if (!$argument->getType()->isInstanceOf($argumentContract->getType())) {
                        throw new \Graphpinator\Exception\Type\InterfaceContractArgumentTypeMismatch();
                    }

                    if (!$argumentContract->getConstraints()->isCovariant($argument->getConstraints())) {
                        throw new \Graphpinator\Exception\Type\ArgumentConstraintNotCovariant();
                    }
                }
            }
        }
    }

    private function printImplements() : string
    {
        if (\count($this->implements) === 0) {
            return '';
        }

        $interfaces = [];

        foreach ($this->implements as $interface) {
            $interfaces[] = $interface->getName();
        }

        return ' implements ' . \implode(' & ', $interfaces);
    }
}
