<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

use \Graphpinator\Typesystem\Argument\ArgumentSet;
use \Graphpinator\Typesystem\Contract\ConcreteType;
use \Graphpinator\Typesystem\Contract\NamedTypeVisitor;
use \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsage;
use \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet;
use \Graphpinator\Typesystem\Exception\DirectiveIncorrectType;
use \Graphpinator\Typesystem\Exception\InputTypeMustDefineOneOreMoreFields;
use \Graphpinator\Typesystem\Location\InputObjectLocation;

abstract class InputType extends ConcreteType implements
    \Graphpinator\Typesystem\Contract\Inputable
{
    use \Graphpinator\Typesystem\Utils\THasDirectives;

    protected const DATA_CLASS = \stdClass::class;

    protected ?ArgumentSet $arguments = null;
    private bool $cycleValidated = false;

    public function __construct()
    {
        $this->directiveUsages = new DirectiveUsageSet();
    }

    final public function getArguments() : ArgumentSet
    {
        if (!$this->arguments instanceof ArgumentSet) {
            $this->arguments = $this->getFieldDefinition();
            $this->afterGetFieldDefinition();

            if (\Graphpinator\Graphpinator::$validateSchema) {
                if ($this->arguments->count() === 0) {
                    throw new InputTypeMustDefineOneOreMoreFields();
                }

                $this->validateCycles([]);
            }
        }

        return $this->arguments;
    }

    final public function accept(NamedTypeVisitor $visitor) : mixed
    {
        return $visitor->visitInput($this);
    }

    final public function getDataClass() : string
    {
        return static::DATA_CLASS;
    }

    final public function addDirective(
        InputObjectLocation $directive,
        array $arguments = [],
    ) : static
    {
        $usage = new DirectiveUsage($directive, $arguments);

        if (\Graphpinator\Graphpinator::$validateSchema && !$directive->validateInputUsage($this, $usage->getArgumentValues())) {
            throw new DirectiveIncorrectType();
        }

        $this->directiveUsages[] = $usage;

        return $this;
    }

    abstract protected function getFieldDefinition() : ArgumentSet;

    /**
     * This function serves to prevent infinite cycles.
     *
     * It doesn't have to be used at all, unless input have arguments self referencing fields and wish to put default value for them.
     */
    protected function afterGetFieldDefinition() : void
    {
    }

    private function validateCycles(array $stack) : void
    {
        if ($this->cycleValidated) {
            return;
        }

        if (\array_key_exists($this->getName(), $stack)) {
            throw new \Graphpinator\Typesystem\Exception\InputCycle();
        }

        $stack[$this->getName()] = true;

        foreach ($this->arguments as $argumentContract) {
            $type = $argumentContract->getType();

            if (!$type instanceof NotNullType) {
                continue;
            }

            $type = $type->getInnerType();

            if (!$type instanceof self) {
                continue;
            }

            if ($type->arguments === null) {
                $type->arguments = $type->getFieldDefinition();
            }

            $type->validateCycles($stack);
        }

        unset($stack[$this->getName()]);
        $this->cycleValidated = true;
    }
}
