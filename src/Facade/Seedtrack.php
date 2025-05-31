<?php

declare(strict_types=1);

namespace Atendwa\Seedtrack\Facade;

use Illuminate\Support\Facades\Facade;

class Seedtrack extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'Seedtrack';
    }
}
