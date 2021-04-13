<?php

declare(strict_types = 1);

namespace Graphpinator\Request\Exception;

final class QueryMissing extends \Graphpinator\Request\Exception\RequestError
{
    public const MESSAGE = 'Invalid request - "query" key not found in request body JSON.';
}
