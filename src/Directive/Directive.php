<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

abstract class Directive
{
    use \Nette\SmartObject;

    protected const NAME = '';
    protected const DESCRIPTION = null;

    private \Graphpinator\Argument\ArgumentSet $arguments;
    private \Closure $evaluate;
    private array $locations;
    private bool $repeatable;

    public function __construct(\Graphpinator\Argument\ArgumentSet $arguments, callable $evaluate, array $locations, bool $repeatable)
    {
        $this->arguments = $arguments;
        $this->evaluate = $evaluate;
        $this->locations = $locations;
        $this->repeatable = $repeatable;
    }

    public function getName() : string
    {
        return static::NAME;
    }

    public function getDescription() : ?string
    {
        return static::DESCRIPTION;
    }

    public function getArguments() : \Graphpinator\Argument\ArgumentSet
    {
        return $this->arguments;
    }

    public function getLocations() : array
    {
        return $this->locations;
    }

    public function isRepeatable() : bool
    {
        return $this->repeatable;
    }
}
