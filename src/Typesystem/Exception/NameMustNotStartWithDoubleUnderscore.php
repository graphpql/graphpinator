<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Exception;

final class NameMustNotStartWithDoubleUnderscore extends TypeError
{
    public const MESSAGE = 'Name "%s" must not start with double underscore (__).';

    public function __construct(
        string $name,
    )
    {
        parent::__construct([$name]);
    }
}
