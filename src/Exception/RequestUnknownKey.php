<?php

declare(strict_types = 1);

namespace Graphpinator\Exception;

final class RequestUnknownKey extends \Graphpinator\Exception\GraphpinatorBase
{
    public const MESSAGE = 'Invalid request - unknown key in request JSON (only "query", "variables" and "operationName" are allowed).';
}
