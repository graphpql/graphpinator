<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Upload;

final class InvalidMap extends \Graphpinator\Exception\Upload\UploadError
{
    public const MESSAGE = 'Invalid map - invalid file map provided in multipart request.';
}
