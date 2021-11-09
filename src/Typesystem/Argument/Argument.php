<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Argument;

use \Graphpinator\Typesystem\Contract\ComponentVisitor;
use \Graphpinator\Typesystem\Contract\Inputable;
use \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsage;
use \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet;
use \Graphpinator\Typesystem\Exception\DirectiveIncorrectType;
use \Graphpinator\Typesystem\Location\ArgumentDefinitionLocation;
use \Graphpinator\Typesystem\Utils\TOptionalDescription;
use \Graphpinator\Value\ArgumentValue;

final class Argument implements \Graphpinator\Typesystem\Contract\Component
{
    use \Nette\SmartObject;
    use TOptionalDescription;
    use \Graphpinator\Typesystem\Utils\THasDirectives;
    use \Graphpinator\Typesystem\Utils\TDeprecatable;

    private ?ArgumentValue $defaultValue = null;

    public function __construct(
        private string $name,
        private Inputable $type,
    )
    {
        $this->directiveUsages = new DirectiveUsageSet();
    }

    public static function create(string $name, \Graphpinator\Typesystem\Contract\Inputable $type) : self
    {
        return new self($name, $type);
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getType() : \Graphpinator\Typesystem\Contract\Inputable
    {
        return $this->type;
    }

    public function getDefaultValue() : ?\Graphpinator\Value\ArgumentValue
    {
        return $this->defaultValue;
    }

    public function setDefaultValue(\stdClass|array|string|int|float|bool|null $defaultValue) : self
    {
        $this->defaultValue = new \Graphpinator\Value\ArgumentValue(
            $this,
            $this->getType()->accept(new \Graphpinator\Value\ConvertRawValueVisitor($defaultValue, new \Graphpinator\Common\Path())),
            false,
        );

        return $this;
    }

    public function accept(ComponentVisitor $visitor) : mixed
    {
        return $visitor->visitArgument($this);
    }

    public function addDirective(
        ArgumentDefinitionLocation $directive,
        array $arguments = [],
    ) : self
    {
        $usage = new DirectiveUsage($directive, $arguments);

        if (\Graphpinator\Graphpinator::$validateSchema && !$directive->validateArgumentUsage($this, $usage->getArgumentValues())) {
            throw new DirectiveIncorrectType();
        }

        $this->directiveUsages[] = $usage;

        return $this;
    }
}
