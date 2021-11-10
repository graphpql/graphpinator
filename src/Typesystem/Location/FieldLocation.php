<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Location;

use \Graphpinator\Value\ArgumentValueSet;

interface FieldLocation extends \Graphpinator\Typesystem\Contract\ExecutableDirective
{
    public const NONE = 'none';
    public const SKIP = 'skip';

    public const ENUM = [
        self::NONE => self::NONE,
        self::SKIP => self::SKIP,
    ];

    public function validateFieldUsage(
        \Graphpinator\Typesystem\Field\Field $field,
        ArgumentValueSet $arguments,
    ) : bool;

    public function resolveFieldBefore(
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : string;

    public function resolveFieldAfter(
        \Graphpinator\Value\ArgumentValueSet $arguments,
        \Graphpinator\Value\FieldValue $fieldValue,
    ) : string;
}
