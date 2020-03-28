<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Field;

final class Field
{
    use \Nette\SmartObject;

    private string $name;
    private \Infinityloop\Graphpinator\Type\Contract\Outputable $type;
    private \Infinityloop\Graphpinator\Argument\ArgumentSet $arguments;
    private $resolveFunction;

    public function __construct(string $name, \Infinityloop\Graphpinator\Type\Contract\Outputable $type, callable $resolveFunction, ?\Infinityloop\Graphpinator\Argument\ArgumentSet $arguments = null)
    {
        $this->name = $name;
        $this->type = $type;
        $this->resolveFunction = $resolveFunction;
        $this->arguments = $arguments instanceof \Infinityloop\Graphpinator\Argument\ArgumentSet
            ? $arguments :
            new \Infinityloop\Graphpinator\Argument\ArgumentSet([]);
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getType() : \Infinityloop\Graphpinator\Type\Contract\Outputable
    {
        return $this->type;
    }

    public function getArguments() : \Infinityloop\Graphpinator\Argument\ArgumentSet
    {
        return $this->arguments;
    }

    public function resolve(ResolveResult $parentValue, \Infinityloop\Graphpinator\Value\ValidatedValueSet $arguments) : ResolveResult
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
