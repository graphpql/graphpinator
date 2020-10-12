<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Upload;

final class OnlyVariablesSupported extends \Graphpinator\Exception\Upload\UploadError
{
    public const MESSAGE = 'Files must be passed to variables.';
}
