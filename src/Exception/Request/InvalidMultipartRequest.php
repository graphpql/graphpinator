<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Request;

final class InvalidMultipartRequest extends \Graphpinator\Exception\Request\RequestError
{
    public const MESSAGE = 'Invalid multipart request - ';
}
