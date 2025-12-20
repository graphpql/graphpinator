<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Validator;

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
        if (\array_key_exists($fragment->name, $this->validated)) {
            return;
        }

        if (\array_key_exists($fragment->name, $this->stack)) {
            throw new FragmentCycle();
        }

        $this->stack[$fragment->name] = true;
        $this->validateFieldSet($fragment->fields);
        unset($this->stack[$fragment->name]);
        $this->validated[$fragment->name] = true;
    }

    private function validateFieldSet(FieldSet $fieldSet) : void
    {
        foreach ($fieldSet as $field) {
            if ($field->children instanceof FieldSet) {
                $this->validateFieldSet($field->children);
            }
        }

        foreach ($fieldSet->getFragmentSpreads() as $spread) {
            if (!$spread instanceof NamedFragmentSpread) {
                continue;
            }

            if (!$this->fragmentSet->offsetExists($spread->name)) {
                throw new UnknownFragment($spread->name);
            }

            $this->validateFragment($this->fragmentSet->offsetGet($spread->name));
        }
    }
}
