<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Type;

final class InputTypeMustDefineOneOreMoreFields extends \Graphpinator\Exception\Type\TypeError
{
    public const MESSAGE = 'An Input Object type must define one or more input fields.';
}
