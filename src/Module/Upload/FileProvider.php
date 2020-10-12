<?php

declare(strict_types = 1);

namespace Graphpinator\Module\Upload;

interface FileProvider
{
    public function getMap() : \Graphpinator\Json;

    public function getFile(string $key) : \Psr\Http\Message\UploadedFileInterface;
}
