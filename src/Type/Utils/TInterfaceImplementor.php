<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Utils;

trait TInterfaceImplementor
{
    protected \Graphpinator\Type\Utils\InterfaceSet $implements;

    public function getInterfaces() : InterfaceSet
    {
        return $this->implements;
    }

   public function implements(\Graphpinator\Type\InterfaceType $interface) : bool
    {
        foreach ($this->implements as $temp) {
            if ($temp->getName() === $interface->getName() || $temp->implements($interface)) {
                return true;
            }
        }

        return false;
    }

    protected function validateInterfaces() : void
    {
        foreach ($this->implements as $interface) {
            foreach ($interface->getFields() as $fieldContract) {
                if (!$this->fields->offsetExists($fieldContract->getName())) {
                    throw new \Exception('Type doesnt satisfy interface - missing field');
                }

                $field = $this->fields->offsetGet($fieldContract->getName());

                if (!$field->getType()->isInstanceOf($fieldContract->getType())) {
                    throw new \Exception('Type doesnt satisfy interface - invalid field type');
                }
            }
        }
    }
}
