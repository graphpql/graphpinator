<?php

declare(strict_types = 1);

namespace Graphpinator\Exception;

final class RequestVariablesNotArray extends \Graphpinator\Exception\GraphpinatorBase
{
    public const MESSAGE = 'Invalid request - "variables" key in request JSON is of invalid type (expected array).';
}
