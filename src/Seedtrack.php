<?php

declare(strict_types=1);

namespace Atendwa\Seedtrack;

use Atendwa\Seedtrack\Attributes\SeederAttribute;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Finder\SplFileInfo;

class Seedtrack
{
    /**
     * @return list<array<string, class-string<Seeder>|int>>
     *
     * @throws ReflectionException
     */
    public function discover(): array
    {
        $except = [
            'resources', 'routes', 'storage', 'stubs', 'tests', 'vendor',
            'bootstrap', 'config', 'database', 'node_modules', 'public',
        ];

        $seeders = collect(File::directories(base_path()))
            ->map(fn ($dir) => str($this->asString($dir))->afterLast('/')->toString())
            ->filter(fn ($dir): bool => ! str($dir)->contains($except))->map(fn ($dir) => File::allFiles(base_path($dir)))
            ->collapse()->filter(fn ($file): bool => $file instanceof SplFileInfo)
            ->filter(fn (SplFileInfo $file) => str($file->getRealPath())->contains('seeder'))
            ->map(fn (SplFileInfo $file): string => $this->getClassFromFile($file->getRealPath()))
            ->filter(fn ($class): bool => class_exists($class))->map(fn ($class): ReflectionClass => new ReflectionClass($class))
            ->filter(fn (ReflectionClass $reflectionClass): bool => $reflectionClass->isSubclassOf(Seeder::class) && ! $reflectionClass->isAbstract())
            ->map(function (ReflectionClass $reflectionClass): ?array {
                $attributes = $reflectionClass->getAttributes(SeederAttribute::class);

                if (blank($attributes)) {
                    return null;
                }

                $seederAttribute = $attributes[0]->newInstance();

                if (app()->isProduction() && ! $seederAttribute->runsInProduction) {
                    return null;
                }

                return ['order' => $seederAttribute->order, 'class' => $reflectionClass->getName()];
            })->filter()->values()->all();

        usort($seeders, fn ($first, $second): int => $first['order'] <=> $second['order']);

        return $seeders;
    }

    /**
     * @return list<class-string<Seeder>|int>
     *
     * @throws ReflectionException
     */
    public function call(): array
    {
        return array_column($this->discover(), 'class');
    }

    private function getClassFromFile(string $path): string
    {
        return str($path)->between(base_path().'/', '.php')->replace('/', '\\')->ucfirst()->toString();
    }

    private function asString(mixed $value): string
    {
        return match (true) {
            is_numeric($value) => (string) $value,
            is_string($value) => $value,
            default => '',
        };
    }
}
