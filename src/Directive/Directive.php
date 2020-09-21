<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

abstract class Directive
{
    use \Nette\SmartObject;
    use \Graphpinator\Printable\TRepeatablePrint;

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

    public function getName() : string
    {
        return static::NAME;
    }

    public function getDescription() : ?string
    {
        return static::DESCRIPTION;
    }

    public function getLocations() : array
    {
        return $this->locations;
    }

    public function isRepeatable() : bool
    {
        return $this->repeatable;
    }

    public function getArguments() : \Graphpinator\Argument\ArgumentSet
    {
        return $this->arguments;
    }

    public function printSchema() : string
    {
        $schema = 'directive @' . $this->getName();

        if ($this->arguments->count() > 0) {
            $schema .= '(' . \PHP_EOL . $this->printItems($this->getArguments()) . ')';
        }

        if ($this->repeatable) {
            $schema .= ' repeatable';
        }

        return $schema . ' on ' . \implode(' | ', $this->locations);
    }
}
