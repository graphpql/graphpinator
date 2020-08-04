<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Request;

final class QueryNotString extends \Graphpinator\Exception\Request\RequestError
{
    public const MESSAGE = 'Invalid request - "query" key in request JSON is of invalid type (expected string).';
}
