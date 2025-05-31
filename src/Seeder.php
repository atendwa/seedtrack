<?php

declare(strict_types=1);

namespace Atendwa\Seedtrack;

use Atendwa\Seedtrack\Concerns\PreventsDuplicateSeeding;

abstract class Seeder extends \Illuminate\Database\Seeder
{
    use PreventsDuplicateSeeding;
}
