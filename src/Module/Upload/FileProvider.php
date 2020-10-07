<?php

declare(strict_types = 1);

namespace Graphpinator\Module\Upload;

interface FileProvider
{
    public function getMap() : \Graphpinator\Json;

    public function getFile(int $key) : \Psr\Http\Message\UploadedFileInterface;
}
