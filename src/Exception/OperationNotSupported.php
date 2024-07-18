<?php

declare(strict_types = 1);

namespace Graphpinator\Exception;

final class OperationNotSupported extends GraphpinatorBase
{
    public const MESSAGE = 'This method is not supported on this object.';
}
