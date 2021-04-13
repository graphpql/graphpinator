<?php

declare(strict_types = 1);

namespace Graphpinator\Request\Exception;

final class InvalidMethod extends \Graphpinator\Request\Exception\RequestError
{
    public const MESSAGE = 'Invalid request - only GET and POST methods are supported.';
}
