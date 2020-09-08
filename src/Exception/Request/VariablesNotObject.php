<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Request;

final class VariablesNotObject extends \Graphpinator\Exception\Request\RequestError
{
    public const MESSAGE = 'Invalid request - "variables" key in request JSON is of invalid type (expected object).';
}
