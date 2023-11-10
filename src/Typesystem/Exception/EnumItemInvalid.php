<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Exception;

final class EnumItemInvalid extends \Graphpinator\Typesystem\Exception\TypeError
{
    public const MESSAGE = 'EnumItem "%s" does not satisfy the format required by GraphQL specification.';

    public function __construct(string $enumItem)
    {
        parent::__construct([$enumItem]);
    }
}
