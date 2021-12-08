<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Exception;

final class FieldDirectiveNotCovariant extends \Graphpinator\Typesystem\Exception\TypeError
{
    public const MESSAGE = 'Type "%s" does not satisfy interface "%s" - field "%s" has directive which is not covariant (%s).';

    public function __construct(string $childName, string $interfaceName, string $fieldName, string $message)
    {
        $this->messageArgs = [$childName, $interfaceName, $fieldName, $message];

        parent::__construct();
    }
}
