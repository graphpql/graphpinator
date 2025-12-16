<?php

declare(strict_types = 1);

namespace Graphpinator\Resolver;

use Graphpinator\Normalizer\FinalizedRequest;
use Graphpinator\Normalizer\Operation\Operation;
use Graphpinator\Parser\OperationType;
use Graphpinator\Result;
use Graphpinator\Typesystem\Location\MutationLocation;
use Graphpinator\Typesystem\Location\QueryLocation;
use Graphpinator\Typesystem\Location\SubscriptionLocation;
use Graphpinator\Value\TypeIntermediateValue;

final class Resolver
{
    public function resolve(FinalizedRequest $finalizedRequest) : Result
    {
        $operation = $finalizedRequest->getOperation();

        return match ($operation->getType()) {
            OperationType::QUERY => $this->resolveQuery($operation),
            OperationType::MUTATION => $this->resolveMutation($operation),
            OperationType::SUBSCRIPTION => $this->resolveSubscription($operation),
        };
    }

    private function resolveQuery(Operation $operation) : Result
    {
        foreach ($operation->getDirectives() as $directive) {
            $directiveDef = $directive->getDirective();
            \assert($directiveDef instanceof QueryLocation);
            $directiveDef->resolveQueryBefore($directive->getArguments());
        }

        $resolver = new ResolveVisitor(
            $operation->getSelections(),
            new TypeIntermediateValue($operation->getRootObject(), null),
        );

        $operationValue = $operation->getRootObject()->accept($resolver);

        foreach ($operation->getDirectives() as $directive) {
            $directiveDef = $directive->getDirective();
            \assert($directiveDef instanceof QueryLocation);
            $directiveDef->resolveQueryAfter($directive->getArguments(), $operationValue);
        }

        return new Result($operationValue);
    }

    private function resolveMutation(Operation $operation) : Result
    {
        foreach ($operation->getDirectives() as $directive) {
            $directiveDef = $directive->getDirective();
            \assert($directiveDef instanceof MutationLocation);
            $directiveDef->resolveMutationBefore($directive->getArguments());
        }

        $resolver = new ResolveVisitor(
            $operation->getSelections(),
            new TypeIntermediateValue($operation->getRootObject(), null),
        );

        $operationValue = $operation->getRootObject()->accept($resolver);

        foreach ($operation->getDirectives() as $directive) {
            $directiveDef = $directive->getDirective();
            \assert($directiveDef instanceof MutationLocation);
            $directiveDef->resolveMutationAfter($directive->getArguments(), $operationValue);
        }

        return new Result($operationValue);
    }

    private function resolveSubscription(Operation $operation) : Result
    {
        foreach ($operation->getDirectives() as $directive) {
            $directiveDef = $directive->getDirective();
            \assert($directiveDef instanceof SubscriptionLocation);
            $directiveDef->resolveSubscriptionBefore($directive->getArguments());
        }

        $resolver = new ResolveVisitor(
            $operation->getSelections(),
            new TypeIntermediateValue($operation->getRootObject(), null),
        );

        $operationValue = $operation->getRootObject()->accept($resolver);

        foreach ($operation->getDirectives() as $directive) {
            $directiveDef = $directive->getDirective();
            \assert($directiveDef instanceof SubscriptionLocation);
            $directiveDef->resolveSubscriptionAfter($directive->getArguments(), $operationValue);
        }

        return new Result($operationValue);
    }
}
