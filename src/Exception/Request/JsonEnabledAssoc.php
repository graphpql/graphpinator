<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Request;

final class JsonEnabledAssoc extends \Graphpinator\Exception\Request\RequestError
{
    public const MESSAGE = 'Invalid request - $assoc parameter must be false in order to correctly recognise JSON structure.';

    protected function isOutputable() : bool
    {
        return false;
    }
}
