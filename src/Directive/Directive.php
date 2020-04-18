<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

abstract class Directive
{
    use \Nette\SmartObject;

    protected const NAME = '';
    protected const DESCRIPTION = null;

    private \Graphpinator\Argument\ArgumentSet $arguments;
    private \Closure $resolveFn;
    private array $locations;
    private bool $repeatable;

    public function __construct(\Graphpinator\Argument\ArgumentSet $arguments, callable $resolveFn, array $locations, bool $repeatable)
    {
        $this->arguments = $arguments;
        $this->resolveFn = $resolveFn;
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

    public function resolve(\Graphpinator\Normalizer\ArgumentValueSet $arguments) : string
    {
        $result = \call_user_func($this->resolveFn, $arguments);

        if (\is_string($result) && \array_key_exists($result, DirectiveResult::ENUM)) {
            return $result;
        }

        throw new \Exception('Directive fn must return directiveResult string');
    }
}
