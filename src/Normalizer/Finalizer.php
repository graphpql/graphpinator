<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

use Graphpinator\Common\Path;
use Graphpinator\Exception\GraphpinatorBase;
use Graphpinator\Normalizer\Operation\Operation;
use Graphpinator\Normalizer\Variable\Variable;
use Graphpinator\Typesystem\Location\VariableDefinitionLocation;
use Graphpinator\Value\InputedValue;
use Graphpinator\Value\Visitor\ConvertRawValueVisitor;

final class Finalizer
{
    private Path $path;

    public function finalize(NormalizedRequest $normalizedRequest, \stdClass $variables, ?string $operationName) : FinalizedRequest
    {
        $this->path = new Path();

        try {
            $operation = $this->selectOperation($normalizedRequest, $operationName);
            $this->path->add($operation->name . ' <operation>');
            $this->applyVariables($operation, $variables);
        } catch (GraphpinatorBase $e) {
            throw $e->setPath($this->path);
        }

        return new FinalizedRequest($operation);
    }

    private function selectOperation(NormalizedRequest $normalizedRequest, ?string $operationName) : Operation
    {
        return $operationName === null
            ? $normalizedRequest->operations->getFirst()
            : $normalizedRequest->operations->offsetGet($operationName);
    }

    private function applyVariables(Operation $operation, \stdClass $variables) : void
    {
        $normalized = [];

        foreach ($operation->variables as $variable) {
            $this->path->add($variable->name . ' <variable>');
            $value = $this->normalizeVariableValue($variable, $variables);

            foreach ($variable->directives as $directive) {
                $directiveDef = $directive->directive;
                \assert($directiveDef instanceof VariableDefinitionLocation);
                $directiveDef->resolveVariableDefinition($directive->arguments, $value);
            }

            $normalized[$variable->name] = $value;
            $this->path->pop();
        }

        $operation->children->applyVariables(new VariableValueSet($normalized));
    }

    private function normalizeVariableValue(
        Variable $variable,
        \stdClass $variables,
    ) : InputedValue
    {
        if (isset($variables->{$variable->name})) {
            return $variable->type->accept(new ConvertRawValueVisitor($variables->{$variable->name}, $this->path));
        }

        if ($variable->defaultValue instanceof InputedValue) {
            return $variable->defaultValue;
        }

        return $variable->type->accept(new ConvertRawValueVisitor(null, $this->path));
    }
}
