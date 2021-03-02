<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Field;

/**
 * @method \Graphpinator\Parser\Field\Field current() : object
 * @method \Graphpinator\Parser\Field\Field offsetGet($offset) : object
 */
final class FieldSet extends \Infinityloop\Utils\ObjectSet
{
    protected const INNER_CLASS = \Graphpinator\Parser\Field\Field::class;

    private \Graphpinator\Parser\FragmentSpread\FragmentSpreadSet $fragments;

    public function __construct(array $fields, \Graphpinator\Parser\FragmentSpread\FragmentSpreadSet $fragments)
    {
        parent::__construct($fields);

        $this->fragments = $fragments;
    }

    public function getFragmentSpreads() : \Graphpinator\Parser\FragmentSpread\FragmentSpreadSet
    {
        return $this->fragments;
    }
}
