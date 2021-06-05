<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

abstract class InputType extends \Graphpinator\Typesystem\Contract\ConcreteType implements
    \Graphpinator\Typesystem\Contract\Inputable
{
    use \Graphpinator\Typesystem\Utils\THasDirectives;

    protected const DATA_CLASS = \stdClass::class;

    protected ?\Graphpinator\Typesystem\Argument\ArgumentSet $arguments = null;
    private bool $cycleValidated = false;

    public function __construct()
    {
        $this->directiveUsages = new \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet();
    }

    final public function getArguments() : \Graphpinator\Typesystem\Argument\ArgumentSet
    {
        if (!$this->arguments instanceof \Graphpinator\Typesystem\Argument\ArgumentSet) {
            $this->arguments = $this->getFieldDefinition();
            $this->afterGetFieldDefinition();

            if (\Graphpinator\Graphpinator::$validateSchema) {
                if ($this->arguments->count() === 0) {
                    throw new \Graphpinator\Typesystem\Exception\InputTypeMustDefineOneOreMoreFields();
                }

                $this->validateCycles([]);
            }
        }

        return $this->arguments;
    }

    final public function accept(\Graphpinator\Typesystem\Contract\NamedTypeVisitor $visitor) : mixed
    {
        return $visitor->visitInput($this);
    }

    final public function getDataClass() : string
    {
        return static::DATA_CLASS;
    }

    final public function addDirective(
        \Graphpinator\Typesystem\Location\InputObjectLocation $directive,
        array $arguments = [],
    ) : static
    {
        $usage = new \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsage($directive, $arguments);

        if (\Graphpinator\Graphpinator::$validateSchema && !$directive->validateInputUsage($this, $usage->getArgumentValues())) {
            throw new \Graphpinator\Typesystem\Exception\DirectiveIncorrectType();
        }

        $this->directiveUsages[] = $usage;

        return $this;
    }

    abstract protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet;

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
