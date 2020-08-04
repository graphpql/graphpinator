<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Request;

final class UnknownKey extends \Graphpinator\Exception\Request\RequestError
{
    public const MESSAGE = 'Invalid request - unknown key in request JSON (only "query", "variables" and "operationName" are allowed).';
}
