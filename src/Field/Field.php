<?php

declare(strict_types = 1);

namespace PGQL\Field;

final class Field
{
    use \Nette\SmartObject;

    private string $name;
    private \PGQL\Type\Contract\Outputable $type;
    private \PGQL\Argument\ArgumentSet $arguments;
    private $resolveFunction;

    public function __construct(string $name, \PGQL\Type\Contract\Outputable $type, callable $resolveFunction, ?\PGQL\Argument\ArgumentSet $arguments = null)
    {
        $this->name = $name;
        $this->type = $type;
        $this->resolveFunction = $resolveFunction;
        $this->arguments = $arguments instanceof \PGQL\Argument\ArgumentSet
            ? $arguments :
            new \PGQL\Argument\ArgumentSet([]);
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getType() : \PGQL\Type\Contract\Outputable
    {
        return $this->type;
    }

    public function getArguments() : \PGQL\Argument\ArgumentSet
    {
        return $this->arguments;
    }

    public function resolve(ResolveResult $parentValue, \PGQL\Value\ValidatedValueSet $arguments) : ResolveResult
    {
        $result = \call_user_func($this->resolveFunction, $parentValue->getResult()->getRawValue(), $arguments);

        if (!$result instanceof ResolveResult) {
            $result = ResolveResult::fromRaw($this->type, $result);
        }

        if (!$result->getType()->isInstanceOf($this->type)) {
            throw new \Exception('Incorrect type of resolved field.');
        }

        return $result;
    }
}
