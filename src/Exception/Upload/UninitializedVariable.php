<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Upload;

final class UninitializedVariable extends \Graphpinator\Exception\Upload\UploadError
{
    public const MESSAGE = 'Variable for Upload must be initialized.';
}
