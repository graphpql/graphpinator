<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Argument;

use Graphpinator\Common\Path;
use Graphpinator\Graphpinator;
use Graphpinator\Typesystem\Contract\Component;
use Graphpinator\Typesystem\Contract\ComponentVisitor;
use Graphpinator\Typesystem\Contract\Inputable;
use Graphpinator\Typesystem\DirectiveUsage\DirectiveUsage;
use Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet;
use Graphpinator\Typesystem\Exception\DirectiveIncorrectType;
use Graphpinator\Typesystem\Location\ArgumentDefinitionLocation;
use Graphpinator\Typesystem\Utils\TDeprecatable;
use Graphpinator\Typesystem\Utils\THasDirectives;
use Graphpinator\Typesystem\Utils\TOptionalDescription;
use Graphpinator\Value\ArgumentValue;
use Graphpinator\Value\ConvertRawValueVisitor;

final class Argument implements Component
{
    use TOptionalDescription;
    use THasDirectives;
    use TDeprecatable;

    private ?ArgumentValue $defaultValue = null;

    public function __construct(
        private string $name,
        private Inputable $type,
    )
    {
        $this->directiveUsages = new DirectiveUsageSet();
    }

    public static function create(string $name, Inputable $type) : self
    {
        return new self($name, $type);
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getType() : Inputable
    {
        return $this->type;
    }

    public function getDefaultValue() : ?ArgumentValue
    {
        return $this->defaultValue;
    }

    public function setDefaultValue(mixed $defaultValue) : self
    {
        $this->defaultValue = new ArgumentValue(
            $this,
            $this->getType()->accept(new ConvertRawValueVisitor($defaultValue, new Path())),
            false,
        );

        return $this;
    }

    #[\Override]
    public function accept(ComponentVisitor $visitor) : mixed
    {
        return $visitor->visitArgument($this);
    }

    /**
     * @param ArgumentDefinitionLocation $directive
     * @phpcs:ignore
     * @param array<string, mixed> $arguments
     */
    public function addDirective(
        ArgumentDefinitionLocation $directive,
        array $arguments = [],
    ) : self
    {
        $usage = new DirectiveUsage($directive, $arguments);

        if (Graphpinator::$validateSchema && !$directive->validateArgumentUsage($this, $usage->getArgumentValues())) {
            throw new DirectiveIncorrectType();
        }

        $this->directiveUsages[] = $usage;

        return $this;
    }
}
