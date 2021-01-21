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

    public function __construct(
        \Graphpinator\Parser\Value\ArgumentValueSet $parsed,
        \Graphpinator\Field\Field|\Graphpinator\Directive\Directive $element,
        \Graphpinator\Normalizer\Variable\VariableSet $variableSet,
    )
    {
        parent::__construct();

        $argumentSet = $element->getArguments();

        foreach ($argumentSet as $argument) {
            if (!$parsed->offsetExists($argument->getName())) {
                $this[] = \Graphpinator\Value\ArgumentValue::fromRaw($argument, null);

                continue;
            }

            $parsedArg = $parsed->offsetGet($argument->getName());
            $this[] = \Graphpinator\Value\ArgumentValue::fromParsed($argument, $parsedArg->getValue(), $variableSet);
        }

        foreach ($parsed as $value) {
            if (!$argumentSet->offsetExists($value->getName())) {
                throw new \Graphpinator\Exception\Normalizer\UnknownArgument($value->getName(), $element->getName());
            }
        }
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
