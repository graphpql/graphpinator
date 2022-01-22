<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

enum NativeEnum : string
{
    #[\Graphpinator\Typesystem\Attribute\Description('Some description for following enum case')]
    case ABC = 'ABC';
    #[\Graphpinator\Typesystem\Attribute\Description('Another description for following enum case')]
    case XYZ = 'XYZ';
}
