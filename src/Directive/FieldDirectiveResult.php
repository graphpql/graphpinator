<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

final class FieldDirectiveResult
{
    use \Nette\StaticClass;

    public const NONE = 'none';
    public const SKIP = 'skip';

    public const ENUM = [
        self::NONE => self::NONE,
        self::SKIP => self::SKIP,
    ];
}
