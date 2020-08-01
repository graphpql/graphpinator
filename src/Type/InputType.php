<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

abstract class InputType extends \Graphpinator\Type\Contract\ConcreteDefinition implements \Graphpinator\Type\Contract\Inputable
{
    use \Graphpinator\Printable\TRepeatablePrint;

    protected ?\Graphpinator\Argument\ArgumentSet $arguments = null;

    final public function createValue($rawValue) : \Graphpinator\Resolver\Value\ValidatedValue
    {
        return \Graphpinator\Resolver\Value\InputValue::create($rawValue, $this);
    }

    final public function applyDefaults($value) : array
    {
        if (!\is_array($value)) {
            throw new \Exception('Composite input type without fields specified.');
        }

        return self::merge($value, $this->getArguments()->getDefaults());
    }

    final public function getArguments() : \Graphpinator\Argument\ArgumentSet
    {
        if (!$this->arguments instanceof \Graphpinator\Argument\ArgumentSet) {
            $this->arguments = $this->getFieldDefinition();
        }

        return $this->arguments;
    }

    final public function getTypeKind() : string
    {
        return \Graphpinator\Type\Introspection\TypeKind::INPUT_OBJECT;
    }

    final public function printSchema() : string
    {
        return $this->printDescription()
            . 'input ' . $this->getName() . ' {' . \PHP_EOL
            . $this->printItems($this->getArguments())
            . '}';
    }

    abstract protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet;

    private static function merge(array $core, iterable $supplement) : array
    {
        foreach ($supplement as $key => $value) {
            if (\array_key_exists($key, $core)) {
                if (\is_array($core[$key])) {
                    $core[$key] = self::merge($core[$key], $supplement[$key]);
                }

                continue;
            }

            $core[$key] = $value;
        }

        return $core;
    }
}
