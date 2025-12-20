<?php

declare(strict_types = 1);

namespace Graphpinator\Resolver;

use Graphpinator\Normalizer\FinalizedRequest;
use Graphpinator\Normalizer\Operation\Operation;
use Graphpinator\Parser\OperationType;
use Graphpinator\Resolver\Visitor\ResolveVisitor;
use Graphpinator\Typesystem\Location\MutationLocation;
use Graphpinator\Typesystem\Location\QueryLocation;
use Graphpinator\Typesystem\Location\SubscriptionLocation;
use Graphpinator\Value\TypeIntermediateValue;

final class Resolver
{
    public function resolve(FinalizedRequest $finalizedRequest) : Result
    {
        $operation = $finalizedRequest->operation;

        return match ($operation->type) {
            OperationType::QUERY => $this->resolveQuery($operation),
            OperationType::MUTATION => $this->resolveMutation($operation),
            OperationType::SUBSCRIPTION => $this->resolveSubscription($operation),
        };
    }

    private function resolveQuery(Operation $operation) : Result
    {
        foreach ($operation->directives as $directive) {
            $directiveDef = $directive->directive;
            \assert($directiveDef instanceof QueryLocation);
            $directiveDef->resolveQueryBefore($directive->arguments);
        }

        $resolver = new ResolveVisitor(
            $operation->children,
            new TypeIntermediateValue($operation->rootObject, null),
        );

        $operationValue = $operation->rootObject->accept($resolver);

        foreach ($operation->directives as $directive) {
            $directiveDef = $directive->directive;
            \assert($directiveDef instanceof QueryLocation);
            $directiveDef->resolveQueryAfter($directive->arguments, $operationValue);
        }

        return new Result($operationValue);
    }

    private function resolveMutation(Operation $operation) : Result
    {
        foreach ($operation->directives as $directive) {
            $directiveDef = $directive->directive;
            \assert($directiveDef instanceof MutationLocation);
            $directiveDef->resolveMutationBefore($directive->arguments);
        }

        $resolver = new ResolveVisitor($operation->children, new TypeIntermediateValue($operation->rootObject, null));
        $operationValue = $operation->rootObject->accept($resolver);

        foreach ($operation->directives as $directive) {
            $directiveDef = $directive->directive;
            \assert($directiveDef instanceof MutationLocation);
            $directiveDef->resolveMutationAfter($directive->arguments, $operationValue);
        }

        return new Result($operationValue);
    }

    private function resolveSubscription(Operation $operation) : Result
    {
        foreach ($operation->directives as $directive) {
            $directiveDef = $directive->directive;
            \assert($directiveDef instanceof SubscriptionLocation);
            $directiveDef->resolveSubscriptionBefore($directive->arguments);
        }

        $resolver = new ResolveVisitor(
            $operation->children,
            new TypeIntermediateValue($operation->rootObject, null),
        );

        $operationValue = $operation->rootObject->accept($resolver);

        foreach ($operation->directives as $directive) {
            $directiveDef = $directive->directive;
            \assert($directiveDef instanceof SubscriptionLocation);
            $directiveDef->resolveSubscriptionAfter($directive->arguments, $operationValue);
        }

        return new Result($operationValue);
    }
}
