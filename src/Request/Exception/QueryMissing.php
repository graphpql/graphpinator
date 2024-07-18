<?php

declare(strict_types = 1);

namespace Graphpinator\Request\Exception;

final class QueryMissing extends RequestError
{
    public const MESSAGE = 'Invalid request - "query" key not found in request body JSON.';
}
