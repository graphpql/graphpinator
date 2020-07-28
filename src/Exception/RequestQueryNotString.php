<?php

declare(strict_types = 1);

namespace Graphpinator\Exception;

final class RequestQueryNotString extends \Graphpinator\Exception\GraphpinatorBase
{
    public const MESSAGE = 'Invalid request - "query" key in request JSON is of invalid type (expected string).';
}
