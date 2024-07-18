<?php

declare(strict_types = 1);

namespace Graphpinator\Request\Exception;

final class UnknownKey extends RequestError
{
    public const MESSAGE = 'Invalid request - unknown key in request JSON (only "query", "variables" and "operationName" are allowed).';
}
