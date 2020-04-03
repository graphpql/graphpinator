<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

final class Schema
{
    use \Nette\SmartObject;

    private Type $query;
    private ?Type $mutation;
    private ?Type $subscription;

    public function __construct(
        Type $query,
        ?Type $mutation = null,
        ?Type $subscription = null
    )
    {
        $this->query = $query;
        $this->mutation = $mutation;
        $this->subscription = $subscription;
    }

    public function getQuery() : Type
    {
        return $this->query;
    }

    public function getMutation() : ?Type
    {
        return $this->mutation;
    }

    public function getSubscription() : ?Type
    {
        return $this->subscription;
    }
}