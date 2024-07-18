<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

use Graphpinator\Common\Path;
use Graphpinator\Exception\GraphpinatorBase;
use Graphpinator\Normalizer\Operation\Operation;
use Graphpinator\Normalizer\Variable\Variable;
use Graphpinator\Typesystem\Location\VariableDefinitionLocation;
use Graphpinator\Value\ConvertRawValueVisitor;
use Graphpinator\Value\InputedValue;

final class Finalizer
{
    private Path $path;

    public function finalize(NormalizedRequest $normalizedRequest, \stdClass $variables, ?string $operationName) : FinalizedRequest
    {
        $this->path = new Path();

        try {
            $operation = $this->selectOperation($normalizedRequest, $operationName);
            $this->path->add($operation->getName() . ' <operation>');
            $this->applyVariables($operation, $variables);
        } catch (GraphpinatorBase $e) {
            throw $e->setPath($this->path);
        }

        return new FinalizedRequest($operation);
    }

    private function selectOperation(NormalizedRequest $normalizedRequest, ?string $operationName) : Operation
    {
        return $operationName === null
            ? $normalizedRequest->getOperations()->getFirst()
            : $normalizedRequest->getOperations()->offsetGet($operationName);
    }

    private function applyVariables(Operation $operation, \stdClass $variables) : void
    {
        $normalized = [];

        foreach ($operation->getVariables() as $variable) {
            $this->path->add($variable->getName() . ' <variable>');
            $value = $this->normalizeVariableValue($variable, $variables);

            foreach ($variable->getDirectives() as $directive) {
                $directiveDef = $directive->getDirective();
                \assert($directiveDef instanceof VariableDefinitionLocation);
                $directiveDef->resolveVariableDefinition($directive->getArguments(), $value);
            }

            $normalized[$variable->getName()] = $value;
            $this->path->pop();
        }

        $operation->getSelections()->applyVariables(new VariableValueSet($normalized));
    }

    private function normalizeVariableValue(
        Variable $variable,
        \stdClass $variables,
    ) : InputedValue
    {
        if (isset($variables->{$variable->getName()})) {
            return $variable->getType()->accept(new ConvertRawValueVisitor($variables->{$variable->getName()}, $this->path));
        }

        if ($variable->getDefaultValue() instanceof InputedValue) {
            return $variable->getDefaultValue();
        }

        return $variable->getType()->accept(new ConvertRawValueVisitor(null, $this->path));
    }
}
