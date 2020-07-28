<?php

declare(strict_types = 1);

namespace Graphpinator\Exception;

final class RequestWithoutQuery extends \Graphpinator\Exception\GraphpinatorBase
{
    public const MESSAGE = 'Invalid request - "query" key not found in request body JSON.';
}
