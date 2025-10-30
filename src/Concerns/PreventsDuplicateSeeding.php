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
        //        $force = $this->command->option('force');

        //        if (Seeder::query()->where('seeder', static::class)->exists() && ! $force) {
        if (Seeder::query()->where('seeder', static::class)->exists()) {
            return;
        }

        $this->execute();

        Seeder::query()->updateOrCreate(['seeder' => static::class]);
    }

    abstract protected function execute(): void;
}
