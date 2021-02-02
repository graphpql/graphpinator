<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

/**
 * @method \Graphpinator\Value\ArgumentValue current() : object
 * @method \Graphpinator\Value\ArgumentValue offsetGet($offset) : object
 */
final class ArgumentValueSet extends \Infinityloop\Utils\ImplicitObjectMap
{
    protected const INNER_CLASS = \Graphpinator\Value\ArgumentValue::class;

    private function __construct(array $data)
    {
        parent::__construct($data);
    }

    public static function fromParsed(
        \Graphpinator\Parser\Value\ArgumentValueSet $parsed,
        \Graphpinator\Field\Field|\Graphpinator\Directive\Directive $element,
        \Graphpinator\Normalizer\Variable\VariableSet $variableSet,
    ) : self
    {
        $items = [];
        $argumentSet = $element->getArguments();

        foreach ($argumentSet as $argument) {
            if (!$parsed->offsetExists($argument->getName())) {
                $items[] = \Graphpinator\Value\ArgumentValue::fromRaw($argument, null);

                continue;
            }

            $parsedArg = $parsed->offsetGet($argument->getName());
            $items[] = \Graphpinator\Value\ArgumentValue::fromParsed($argument, $parsedArg->getValue(), $variableSet);
        }

        foreach ($parsed as $value) {
            if (!$argumentSet->offsetExists($value->getName())) {
                throw new \Graphpinator\Exception\Normalizer\UnknownArgument($value->getName(), $element->getName());
            }
        }

        return new self($items);
    }

    public static function fromRaw(
        array $rawValues,
        \Graphpinator\Field\Field|\Graphpinator\Directive\Directive $element,
    ) : self
    {
        $items = [];
        $argumentSet = $element->getArguments();

        foreach ($argumentSet as $argument) {
            if (!\array_key_exists($argument->getName(), $rawValues)) {
                $items[] = \Graphpinator\Value\ArgumentValue::fromRaw($argument, null);

                continue;
            }

            $items[] = \Graphpinator\Value\ArgumentValue::fromRaw($argument, $rawValues[$argument->getName()]);
        }

        foreach ($rawValues as $key => $value) {
            if (!$argumentSet->offsetExists($key)) {
                throw new \Graphpinator\Exception\Normalizer\UnknownArgument($key, $element->getName());
            }
        }

        return new self($items);
    }

    public function getValuesForResolver() : array
    {
        $return = [];

        foreach ($this as $argumentValue) {
            $return[] = $argumentValue->getValue()->getRawValue(true);
        }

        return $return;
    }

    public function applyVariables(\Graphpinator\Normalizer\VariableValueSet $variables) : void
    {
        foreach ($this as $value) {
            $value->applyVariables($variables);
        }
    }

    protected function getKey(object $object) : string
    {
        \assert($object instanceof ArgumentValue);

        return $object->getArgument()->getName();
    }
}
