<?php

declare(strict_types = 1);

namespace Graphpinator\Exception;

final class RequestOperationNameNotString extends \Graphpinator\Exception\GraphpinatorBase
{
    public const MESSAGE = 'Invalid request - "operationName" key in request JSON is of invalid type (expected string).';
}
