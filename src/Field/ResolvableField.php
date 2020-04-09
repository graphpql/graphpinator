<?php

declare(strict_types = 1);

namespace Graphpinator\Field;

use Graphpinator\Resolver\FieldResult;

final class ResolvableField extends Field
{
    private $resolveFunction;

    public function __construct(string $name, \Graphpinator\Type\Contract\Outputable $type, callable $resolveFunction, ?\Graphpinator\Argument\ArgumentSet $arguments = null)
    {
        parent::__construct($name, $type, $arguments);
        $this->resolveFunction = $resolveFunction;
    }

    public function resolve(FieldResult $parentValue, \Graphpinator\Normalizer\ArgumentValueSet $arguments) : FieldResult
    {
        $result = \call_user_func($this->resolveFunction, $parentValue->getResult()->getRawValue(), $arguments);

        if (!$result instanceof FieldResult) {
            if (!$this->type->getNamedType() instanceof \Graphpinator\Type\Contract\ConcreteDefinition) {
                throw new \Exception('Abstract type fields need to return ResolveResult with concrete resolution.');
            }

            $result = FieldResult::fromRaw($this->type, $result);
        }

        if ($result->getType()->isInstanceOf($this->type)) {
            return $result;
        }

        throw new \Exception('Incorrect type of resolved field.');
    }
}
