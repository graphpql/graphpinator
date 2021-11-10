<?php

declare(strict_types = 1);

namespace Graphpinator\Resolver;

use \Graphpinator\Normalizer\Operation\Operation;
use \Graphpinator\Result;
use \Graphpinator\Tokenizer\OperationType;
use \Graphpinator\Value\TypeIntermediateValue;

final class Resolver
{
    use \Nette\SmartObject;

    public function resolve(\Graphpinator\Normalizer\FinalizedRequest $finalizedRequest) : Result
    {
        $operation = $finalizedRequest->getOperation();

        return match ($operation->getType()) {
            OperationType::QUERY => $this->resolveQuery($operation),
            \Graphpinator\Tokenizer\OperationType::MUTATION => $this->resolveMutation($operation),
            \Graphpinator\Tokenizer\OperationType::SUBSCRIPTION => $this->resolveSubscription($operation),
        };
    }

    private function resolveQuery(Operation $operation) : Result
    {
        foreach ($operation->getDirectives() as $directive) {
            $directiveDef = $directive->getDirective();
            \assert($directiveDef instanceof \Graphpinator\Typesystem\Location\QueryLocation);
            $directiveDef->resolveQueryBefore($directive->getArguments());
        }

        $resolver = new ResolveVisitor(
            $operation->getSelections(),
            new TypeIntermediateValue($operation->getRootObject(), null),
        );

        $operationValue = $operation->getRootObject()->accept($resolver);

        foreach ($operation->getDirectives() as $directive) {
            $directiveDef = $directive->getDirective();
            \assert($directiveDef instanceof \Graphpinator\Typesystem\Location\QueryLocation);
            $directiveDef->resolveQueryAfter($directive->getArguments(), $operationValue);
        }

        return new Result($operationValue);
    }

    private function resolveMutation(\Graphpinator\Normalizer\Operation\Operation $operation) : Result
    {
        foreach ($operation->getDirectives() as $directive) {
            $directiveDef = $directive->getDirective();
            \assert($directiveDef instanceof \Graphpinator\Typesystem\Location\MutationLocation);
            $directiveDef->resolveMutationBefore($directive->getArguments());
        }

        $resolver = new \Graphpinator\Resolver\ResolveVisitor(
            $operation->getSelections(),
            new \Graphpinator\Value\TypeIntermediateValue($operation->getRootObject(), null),
        );

        $operationValue = $operation->getRootObject()->accept($resolver);

        foreach ($operation->getDirectives() as $directive) {
            $directiveDef = $directive->getDirective();
            \assert($directiveDef instanceof \Graphpinator\Typesystem\Location\MutationLocation);
            $directiveDef->resolveMutationAfter($directive->getArguments(), $operationValue);
        }

        return new Result($operationValue);
    }

    private function resolveSubscription(\Graphpinator\Normalizer\Operation\Operation $operation) : Result
    {
        foreach ($operation->getDirectives() as $directive) {
            $directiveDef = $directive->getDirective();
            \assert($directiveDef instanceof \Graphpinator\Typesystem\Location\SubscriptionLocation);
            $directiveDef->resolveSubscriptionBefore($directive->getArguments());
        }

        $resolver = new \Graphpinator\Resolver\ResolveVisitor(
            $operation->getSelections(),
            new \Graphpinator\Value\TypeIntermediateValue($operation->getRootObject(), null),
        );

        $operationValue = $operation->getRootObject()->accept($resolver);

        foreach ($operation->getDirectives() as $directive) {
            $directiveDef = $directive->getDirective();
            \assert($directiveDef instanceof \Graphpinator\Typesystem\Location\SubscriptionLocation);
            $directiveDef->resolveSubscriptionAfter($directive->getArguments(), $operationValue);
        }

        return new Result($operationValue);
    }
}
