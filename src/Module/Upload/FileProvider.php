<?php

declare(strict_types = 1);

namespace Graphpinator\Module\Upload;

interface FileProvider
{
    public function getMap() : ?\Infinityloop\Utils\Json\MapJson;

    public function getFile(string $key) : \Psr\Http\Message\UploadedFileInterface;
}
