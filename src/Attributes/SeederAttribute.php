<?php

declare(strict_types=1);

namespace Atendwa\Seedtrack\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class SeederAttribute
{
    public function __construct(public bool $runsInProduction = true, public int $order = 0) {}
}
