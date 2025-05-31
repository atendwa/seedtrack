<?php

declare(strict_types=1);

namespace Atendwa\Seedtrack\Console\Commands;

use Atendwa\Seedtrack\Models\Seeder;
use Atendwa\Seedtrack\Seedtrack;
use Illuminate\Console\Command;
use ReflectionException;

class DiscoverSeeders extends Command
{
    protected $signature = 'seeders:list';

    protected $description = 'Lists discovered seeder classes';

    /**
     * @throws ReflectionException
     */
    public function handle(): void
    {
        $seeders = Seeder::all();

        $discovered = collect(app(Seedtrack::class)->discover())->map(fn ($seeder) => array_merge($seeder, [
            'seeded' => $seeders->where('seeder', $seeder['class'])->isNotEmpty() ? 'Yes' : 'No',
        ]));

        $this->table(['Order', 'Class', 'Seeded'], $discovered->values()->all());
    }
}
