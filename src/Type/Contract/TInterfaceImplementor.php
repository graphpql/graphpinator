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
    abstract protected function getFieldDefinition() : \Graphpinator\Field\FieldSet;

    /**
     * Method to validate contract defined by interfaces - whether fields and their type match.
     */
    protected function validateInterfaces() : void
    {
        foreach ($this->implements as $interface) {
            $interface->getDirectiveUsages()->validateInvariance($this->getDirectiveUsages());

            foreach ($interface->getFields() as $fieldContract) {
                if (!$this->getFields()->offsetExists($fieldContract->getName())) {
                    throw new \Graphpinator\Exception\Type\InterfaceContractMissingField(
                        $this->getName(),
                        $interface->getName(),
                        $fieldContract->getName(),
                    );
                }

                $field = $this->getFields()->offsetGet($fieldContract->getName());

                if (!$fieldContract->getType()->isInstanceOf($field->getType())) {
                    throw new \Graphpinator\Exception\Type\InterfaceContractFieldTypeMismatch(
                        $this->getName(),
                        $interface->getName(),
                        $fieldContract->getName(),
                    );
                }

                try {
                    $fieldContract->getDirectiveUsages()->validateCovariance($field->getDirectiveUsages());
                } catch (\Throwable) {
                    // TODO: print information from received exception
                    throw new \Graphpinator\Exception\Type\FieldDirectiveNotCovariant(
                        $this->getName(),
                        $interface->getName(),
                        $fieldContract->getName(),
                    );
                }

                foreach ($fieldContract->getArguments() as $argumentContract) {
                    if (!$field->getArguments()->offsetExists($argumentContract->getName())) {
                        throw new \Graphpinator\Exception\Type\InterfaceContractMissingArgument(
                            $this->getName(),
                            $interface->getName(),
                            $fieldContract->getName(),
                            $argumentContract->getName(),
                        );
                    }

                    $argument = $field->getArguments()->offsetGet($argumentContract->getName());

                    if (!$argument->getType()->isInstanceOf($argumentContract->getType())) {
                        throw new \Graphpinator\Exception\Type\InterfaceContractArgumentTypeMismatch(
                            $this->getName(),
                            $interface->getName(),
                            $fieldContract->getName(),
                            $argumentContract->getName(),
                        );
                    }

                    try {
                        $argumentContract->getDirectiveUsages()->validateContravariance($argument->getDirectiveUsages());
                    } catch (\Throwable) {
                        // TODO: print information from received exception
                        throw new \Graphpinator\Exception\Type\ArgumentDirectiveNotContravariant(
                            $this->getName(),
                            $interface->getName(),
                            $fieldContract->getName(),
                            $argumentContract->getName(),
                        );
                    }
                }
                
                if ($field->getArguments()->count() !== $fieldContract->getArguments()->count()) {
                    foreach ($field->getArguments() as $argument) {
                        if (!$fieldContract->getArguments()->offsetExists($argument->getName()) && $argument->getDefaultValue() === null) {
                            throw new \Graphpinator\Exception\Type\InterfaceContractNewArgumentWithoutDefault(
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
    }
}
