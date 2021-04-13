<?php

declare(strict_types = 1);

namespace Graphpinator\Request\Exception;

final class VariablesNotObject extends \Graphpinator\Request\Exception\RequestError
{
    public const MESSAGE = 'Invalid request - "variables" key in request JSON is of invalid type (expected object).';
}
