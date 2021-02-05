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
                \Graphpinator\Directive\TypeSystemDirectiveLocation::INPUT_OBJECT,
                \Graphpinator\Directive\TypeSystemDirectiveLocation::INTERFACE,
                \Graphpinator\Directive\TypeSystemDirectiveLocation::OBJECT,
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

    protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
    {
        return new \Graphpinator\Argument\ArgumentSet([
            \Graphpinator\Argument\Argument::create('atLeastOne', \Graphpinator\Container\Container::String()->notNull()->list())
                ->addDirective(
                    \Graphpinator\Container\Container::directiveListConstraint(),
                    ['minCount' => 1],
                ),
            \Graphpinator\Argument\Argument::create('exactlyOne', \Graphpinator\Container\Container::String()->notNull()->list())
                ->addDirective(
                    \Graphpinator\Container\Container::directiveListConstraint(),
                    ['minCount' => 1],
                ),
        ]);
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
        // nothing here
    }

    public function resolveInputObject(
        \Graphpinator\Value\InputValue $inputValue,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : void
    {
        // nothing here
    }

    public function resolveArgumentDefinition(
        \Graphpinator\Value\ArgumentValue $argumentValue,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : void
    {
        // nothing here
    }
}
