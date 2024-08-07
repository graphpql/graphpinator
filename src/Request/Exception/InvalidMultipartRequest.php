<?php

declare(strict_types = 1);

namespace Graphpinator\Request\Exception;

final class InvalidMultipartRequest extends RequestError
{
    public const MESSAGE = 'Invalid multipart request - request must be POST and contain "operations" data.';
}
