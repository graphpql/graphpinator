<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

abstract class Directive
{
    use \Nette\SmartObject;

    protected const NAME = '';
    protected const DESCRIPTION = null;

    private array $locations;
    private bool $repeatable;

    public function __construct(array $locations, bool $repeatable)
    {
        $this->locations = $locations;
        $this->repeatable = $repeatable;
    }

    abstract public function getArguments() : \Graphpinator\Argument\ArgumentSet;

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

    public function printSchema() : string
    {
        $schema = 'directive @' . $this->getName();

        if ($this->repeatable) {
            $schema .= ' repeatable';
        }

        return $schema . ' on ' . \implode(' | ', $this->locations);
    }
}
