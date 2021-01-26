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
    private \Graphpinator\Argument\ArgumentSet $arguments;

    public function __construct(array $locations, bool $repeatable, \Graphpinator\Argument\ArgumentSet $arguments)
    {
        $this->locations = $locations;
        $this->repeatable = $repeatable;
        $this->arguments = $arguments;
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
        return $this->arguments;
    }

    final public function printSchema() : string
    {
        $schema = $this->printDescription() . 'directive @' . $this->getName();

        if ($this->arguments->count() > 0) {
            $schema .= '(' . \PHP_EOL . $this->printItems($this->getArguments(), 1) . ')';
        }

        if ($this->repeatable) {
            $schema .= ' repeatable';
        }

        return $schema . ' on ' . \implode(' | ', $this->locations);
    }
}
