<?php

declare(strict_types = 1);

namespace Graphpinator\Field;

use Graphpinator\Request\ResolveResult;

final class ResolvableField
{
    use \Nette\SmartObject;

    private string $name;
    private \Graphpinator\Type\Contract\Outputable $type;
    private \Graphpinator\Argument\ArgumentSet $arguments;
    private $resolveFunction;

    public function __construct(string $name, \Graphpinator\Type\Contract\Outputable $type, callable $resolveFunction, ?\Graphpinator\Argument\ArgumentSet $arguments = null)
    {
        $this->name = $name;
        $this->type = $type;
        $this->resolveFunction = $resolveFunction;
        $this->arguments = $arguments ?? new \Graphpinator\Argument\ArgumentSet([]);
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getType() : \Graphpinator\Type\Contract\Outputable
    {
        return $this->type;
    }

    public function getArguments() : \Graphpinator\Argument\ArgumentSet
    {
        return $this->arguments;
    }

    public function resolve(ResolveResult $parentValue, \Graphpinator\Request\ArgumentValueSet $arguments) : ResolveResult
    {
        $result = \call_user_func($this->resolveFunction, $parentValue->getResult()->getRawValue(), $arguments);

        if (!$result instanceof ResolveResult) {
            if (!$this->type->getNamedType() instanceof \Graphpinator\Type\Contract\ConcreteDefinition) {
                throw new \Exception('Abstract type fields need to return ResolveResult with concrete resolution.');
            }

            $result = ResolveResult::fromRaw($this->type, $result);
        }

        if (!$result->getType()->isInstanceOf($this->type)) {
            throw new \Exception('Incorrect type of resolved field.');
        }

        return $result;
    }
}
