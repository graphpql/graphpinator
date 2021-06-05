<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Exception;

final class FieldDirectiveNotCovariant extends \Graphpinator\Typesystem\Exception\TypeError
{
    public const MESSAGE = 'Type "%s" does not satisfy interface "%s" - field "%s" has directive which is not covariant.';

    public function __construct(string $childName, string $interfaceName, string $fieldName)
    {
        $this->messageArgs = [$childName, $interfaceName, $fieldName];

        parent::__construct();
    }
}
