<?php

declare(strict_types = 1);

namespace Graphpinator\Resolver;

final class Resolver
{
    use \Nette\SmartObject;

    public function resolve(\Graphpinator\Normalizer\Operation $operation, \Infinityloop\Utils\Json $variables) : \Graphpinator\Resolver\OperationResult
    {
        return $operation->execute($variables);
    }
}
