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
     * Returns fields defined for this type.
     */
    public function getFields() : \Graphpinator\Field\FieldSet
    {
        if (!$this->fields instanceof \Graphpinator\Field\FieldSet) {
            $this->fields = $this->getFieldDefinition();

            $this->validateInterfaces();
        }

        return $this->fields;
    }

    /**
     * Checks whether this type implements given interface.
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
            foreach ($interface->getFields() as $fieldContract) {
                if (!$this->getFields()->offsetExists($fieldContract->getName())) {
                    throw new \Exception('Type doesnt satisfy interface - missing field');
                }

                $field = $this->getFields()->offsetGet($fieldContract->getName());

                if (!$field->getType()->isInstanceOf($fieldContract->getType())) {
                    throw new \Exception('Type doesnt satisfy interface - invalid field type');
                }
            }
        }
    }
}
