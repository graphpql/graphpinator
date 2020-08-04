<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Request;

final class QueryMissing extends \Graphpinator\Exception\Request\RequestError
{
    public const MESSAGE = 'Invalid request - "query" key not found in request body JSON.';
}
