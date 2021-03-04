<?php

declare(strict_types = 1);

namespace Graphpinator\Request\Exception;

final class QueryNotString extends \Graphpinator\Request\Exception\RequestError
{
    public const MESSAGE = 'Invalid request - "query" key in request JSON is of invalid type (expected string).';
}
