<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Field;

final class FieldNotDefined extends \Graphpinator\Exception\Field\FieldError
{
    public const MESSAGE = 'Field is not defined.';
}
