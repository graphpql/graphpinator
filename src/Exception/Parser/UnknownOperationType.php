<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class UnknownOperationType extends \Graphpinator\Exception\Parser\ParserError
{
    public const MESSAGE = 'Unknown operation type - one of: query, mutation, subscription (case-sensitive).';
}
