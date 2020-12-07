<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class DuplicateArgument extends \Graphpinator\Exception\Parser\ParserError
{
    public const MESSAGE = 'Argument with name "%s" already exists on current field.';

    public function __construct(string $name, \Graphpinator\Source\Location $location)
    {
        $this->messageArgs = [$name];

        parent::__construct($location);
    }
}
