<?php

declare(strict_types = 1);

namespace Graphpinator\Request\Exception;

final class OperationNameNotString extends RequestError
{
    public const MESSAGE = 'Invalid request - "operationName" key in request JSON is of invalid type (expected string).';
}
