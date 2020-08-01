<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Type;

final class InvalidResolvedValue extends \Graphpinator\Exception\Type\TypeError
{
    public const MESSAGE = 'Invalid resolved value for ';

    public function __construct(
        string $name,
        ?\Graphpinator\Source\Location $location = null,
        ?\Graphpinator\Exception\Path $path = null,
        ?array $extensions = null
    )
    {
        parent::__construct($location, $path, $extensions, self::MESSAGE . $name);
    }
}
