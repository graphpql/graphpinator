<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Exception;

final class InputTypeMustDefineOneOreMoreFields extends \Graphpinator\Typesystem\Exception\TypeError
{
    public const MESSAGE = 'An Input Object type must define one or more input fields.';
}
