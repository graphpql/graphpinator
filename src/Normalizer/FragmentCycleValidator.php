<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

use Graphpinator\Normalizer\Exception\FragmentCycle;
use Graphpinator\Normalizer\Exception\UnknownFragment;
use Graphpinator\Parser\Field\FieldSet;
use Graphpinator\Parser\Fragment\Fragment;
use Graphpinator\Parser\Fragment\FragmentSet;
use Graphpinator\Parser\FragmentSpread\NamedFragmentSpread;

final class FragmentCycleValidator
{
    private array $stack = [];
    private array $validated = [];

    public function __construct(
        private FragmentSet $fragmentSet,
    )
    {
    }

    public function validate() : void
    {
        foreach ($this->fragmentSet as $fragment) {
            $this->validateFragment($fragment);
        }
    }

    private function validateFragment(Fragment $fragment) : void
    {
        if (\array_key_exists($fragment->getName(), $this->validated)) {
            return;
        }

        if (\array_key_exists($fragment->getName(), $this->stack)) {
            throw new FragmentCycle();
        }

        $this->stack[$fragment->getName()] = true;
        $this->validateFieldSet($fragment->getFields());
        unset($this->stack[$fragment->getName()]);
        $this->validated[$fragment->getName()] = true;
    }

    private function validateFieldSet(FieldSet $fieldSet) : void
    {
        foreach ($fieldSet as $field) {
            if ($field->getFields() instanceof FieldSet) {
                $this->validateFieldSet($field->getFields());
            }
        }

        foreach ($fieldSet->getFragmentSpreads() as $spread) {
            if (!$spread instanceof NamedFragmentSpread) {
                continue;
            }

            if (!$this->fragmentSet->offsetExists($spread->getName())) {
                throw new UnknownFragment($spread->getName());
            }

            $this->validateFragment($this->fragmentSet->offsetGet($spread->getName()));
        }
    }
}
