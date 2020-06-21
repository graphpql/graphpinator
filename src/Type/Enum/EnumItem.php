<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Enum;

final class EnumItem implements \Graphpinator\Printable\Printable
{
    use \Nette\SmartObject;
    use \Graphpinator\Utils\TOptionalDescription;
    use \Graphpinator\Utils\TDeprecatable;

    private string $name;

    public function __construct(string $name, ?string $description = null)
    {
        $this->name = $name;
        $this->description = $description;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function printSchema(int $indentLevel = 1) : string
    {
        return $this->printDescription($indentLevel) . $this->getName() . $this->printDeprecated();
    }
}
