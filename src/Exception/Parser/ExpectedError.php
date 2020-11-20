<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

abstract class ExpectedError extends \Graphpinator\Exception\Parser\ParserError
{
    final public function __construct(\Graphpinator\Common\Location $location, string $token)
    {
        $this->messageArgs[] = $token;

        parent::__construct($location);
    }
}
