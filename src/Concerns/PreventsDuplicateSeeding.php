<?php

declare(strict_types=1);

namespace Atendwa\Seedtrack\Concerns;

use Atendwa\Seedtrack\Models\Seeder;
use Exception;

trait PreventsDuplicateSeeding
{
    /**
     * @throws Exception
     */
    public function run(): void
    {
        if (Seeder::query()->where('seeder', static::class)->exists()) {
            return;
        }

        $this->execute();

        Seeder::query()->create(['seeder' => static::class]);
    }

    abstract protected function execute(): void;
}
