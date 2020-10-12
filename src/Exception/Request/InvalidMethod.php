<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Request;

final class InvalidMethod extends \Graphpinator\Exception\Request\RequestError
{
    public const MESSAGE = 'Invalid request - only GET and POST methods are supported.';
}
