<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Exception;

final class OneOfInputInvalidFields extends \Graphpinator\Typesystem\Exception\TypeError
{
    public const MESSAGE = 'OneOf input type must have only nullable fields without default values.';
}
