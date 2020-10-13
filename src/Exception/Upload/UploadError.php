<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Upload;

abstract class UploadError extends \Graphpinator\Exception\GraphpinatorBase
{
    protected function isOutputable() : bool
    {
        return true;
    }
}
