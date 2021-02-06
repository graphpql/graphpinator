<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

abstract class Directive implements \Graphpinator\Directive\Contract\Definition
{
    use \Nette\SmartObject;
    use \Graphpinator\Printable\TRepeatablePrint;
    use \Graphpinator\Utils\TTypeSystemElement;

    protected const NAME = '';
    protected const DESCRIPTION = null;

    private array $locations;
    private bool $repeatable;
    private ?\Graphpinator\Argument\ArgumentSet $arguments = null;

    public function __construct(array $locations, bool $repeatable)
    {
        $this->locations = $locations;
        $this->repeatable = $repeatable;
    }

    final public function getName() : string
    {
        return static::NAME;
    }

    final public function getDescription() : ?string
    {
        return static::DESCRIPTION;
    }

    final public function getLocations() : array
    {
        return $this->locations;
    }

    final public function isRepeatable() : bool
    {
        return $this->repeatable;
    }

    final public function getArguments() : \Graphpinator\Argument\ArgumentSet
    {
        if (!$this->arguments instanceof \Graphpinator\Argument\ArgumentSet) {
            $this->arguments = $this->getFieldDefinition();
        }

        return $this->arguments;
    }

    final public function printSchema() : string
    {
        $schema = $this->printDescription() . 'directive @' . $this->getName();

        if ($this->getArguments()->count() > 0) {
            $schema .= '(' . \PHP_EOL . $this->printItems($this->getArguments(), 1) . ')';
        }

        if ($this->repeatable) {
            $schema .= ' repeatable';
        }

        return $schema . ' on ' . \implode(' | ', $this->locations);
    }

    abstract protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet;
}
