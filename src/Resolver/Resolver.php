<?php

declare(strict_types = 1);

namespace Graphpinator\Resolver;

final class Resolver
{
    use \Nette\SmartObject;

    public function resolve(
        \Graphpinator\Normalizer\Operation\OperationSet $operationSet,
        ?string $operationName,
        array $variables
    ) : \Graphpinator\Resolver\OperationResult
    {
        return $operationSet->execute($operationName, $variables);
    }
}
