<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class UrlType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'url';
    protected const DESCRIPTION = 'This add on scalar validates url string input via filter_var function.
    Examples - http://foo.com/blah_blah, http://foo.com/blah_blah/, http://foo.com/blah_blah_(wikipedia)';

    protected function validateNonNullValue($rawValue) : bool
    {
        return \is_string($rawValue) &&
            (bool) \filter_var($rawValue, \FILTER_VALIDATE_URL);
    }
}
