<?php

declare(strict_types = 1);

namespace Graphpinator\Module\Upload;

final class UploadType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'Upload';
    protected const DESCRIPTION = 'Upload type - represents file which was send to server.'
    . \PHP_EOL . 'By GraphQL viewpoint it is scalar type, but it must be used as input only.';
    
    public function validateNonNullValue($rawValue) : bool
    {
        return $rawValue instanceof \Psr\Http\Message\UploadedFileInterface;
    }
}
