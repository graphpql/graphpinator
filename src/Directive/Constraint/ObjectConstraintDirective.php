<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Constraint;

final class ObjectConstraintDirective extends \Graphpinator\Directive\Directive
    implements \Graphpinator\Directive\Contract\TypeSystemDefinition
{
    protected const NAME = 'objectConstraint';
    protected const DESCRIPTION = 'Graphpinator objectConstraint directive.';

    public function __construct()
    {
        parent::__construct(
            [
                \Graphpinator\Directive\TypeSystemDirectiveLocation::OBJECT,
                \Graphpinator\Directive\TypeSystemDirectiveLocation::INTERFACE,
                \Graphpinator\Directive\TypeSystemDirectiveLocation::INPUT_OBJECT,
            ],
            true,
        );
    }

    public function validateType(
        ?\Graphpinator\Type\Contract\Definition $definition,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : bool
    {
        if ($definition instanceof \Graphpinator\Type\InputType) {
            $fields = $definition->getArguments();
        } elseif ($definition instanceof \Graphpinator\Type\Type || $definition instanceof \Graphpinator\Type\InterfaceType) {
            $fields = $definition->getFields();
        } else {
            return false;
        }

        $atLeastOne = $arguments->offsetGet('atLeastOne');
        $exactlyOne = $arguments->offsetGet('exactlyOne');

        if ($atLeastOne instanceof \Graphpinator\Value\ListValue) {
            foreach ($atLeastOne as $item) {
                if ($fields->offsetExists($item->getRawValue())) {
                    return false;
                }
            }
        }

        if ($exactlyOne instanceof \Graphpinator\Value\ListValue) {
            foreach ($exactlyOne as $item) {
                if ($fields->offsetExists($item->getRawValue())) {
                    return false;
                }
            }
        }

        return true;
    }

    public function resolveFieldDefinitionBefore(
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : void
    {
        // nothing here
    }

    public function resolveFieldDefinitionAfter(
        \Graphpinator\Value\FieldValue $fieldValue,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : void
    {
        // nothing here
    }

    public function resolveObject(
        \Graphpinator\Value\TypeValue $typeValue,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : void
    {
        $this->validate($typeValue, $arguments);
    }

    public function resolveInputObject(
        \Graphpinator\Value\InputValue $inputValue,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : void
    {
        $this->validate($inputValue, $arguments);
    }

    public function resolveArgumentDefinition(
        \Graphpinator\Value\ArgumentValue $argumentValue,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : void
    {
        // nothing here
    }

    protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
    {
        return new \Graphpinator\Argument\ArgumentSet([
            \Graphpinator\Argument\Argument::create('atLeastOne', \Graphpinator\Container\Container::String()->notNull()->list())
                ->addDirective(
                    \Graphpinator\Container\Container::directiveListConstraint(),
                    ['minItems' => 1],
                ),
            \Graphpinator\Argument\Argument::create('exactlyOne', \Graphpinator\Container\Container::String()->notNull()->list())
                ->addDirective(
                    \Graphpinator\Container\Container::directiveListConstraint(),
                    ['minItems' => 1],
                ),
        ]);
    }

    private function validate(
        \Graphpinator\Value\TypeValue|\Graphpinator\Value\InputValue $value,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : void
    {
        $atLeastOne = $arguments->offsetGet('atLeastOne')->getValue()->getRawValue();
        $exactlyOne = $arguments->offsetGet('exactlyOne')->getValue()->getRawValue();

        if (\is_array($atLeastOne)) {
            $valid = false;

            foreach ($atLeastOne as $fieldName) {
                if (isset($value->{$fieldName}) && $value->{$fieldName}->getValue() instanceof \Graphpinator\Value\NullValue) {
                    continue;
                }

                $valid = true;

                break;
            }

            if (!$valid) {
                throw new \Graphpinator\Exception\Constraint\AtLeastOneConstraintNotSatisfied();
            }
        }

        if (!\is_array($exactlyOne)) {
            return;
        }

        $count = 0;
        $notRequested = 0;

        foreach ($exactlyOne as $fieldName) {
            // fields were not requested and are not included in final value
            if (!isset($value->{$fieldName})) {
                ++$notRequested;

                continue;
            }

            if ($value->{$fieldName}->getValue() instanceof \Graphpinator\Value\NullValue) {
                continue;
            }

            ++$count;
        }

        if ($count > 1 || ($count === 0 && $notRequested === 0)) {
            throw new \Graphpinator\Exception\Constraint\ExactlyOneConstraintNotSatisfied();
        }
    }
}
