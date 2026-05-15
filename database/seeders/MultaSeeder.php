<?php

namespace Database\Seeders;

use App\Models\Multa;
use App\Models\Veiculo;
use Illuminate\Database\Seeder;

class MultaSeeder extends Seeder
{
    /**
     * Cria multas associadas a parte dos veículos (0 a 4 por veículo).
     */
    public function run(): void
    {
        Multa::query()->delete();

        foreach (Veiculo::query()->orderBy('id')->cursor() as $veiculo) {
            $quantidade = fake()->randomElement([0, 0, 1, 1, 1, 2, 2, 2, 3, 4]);

            if ($quantidade === 0) {
                continue;
            }

            Multa::factory()
                ->count($quantidade)
                ->for($veiculo)
                ->create();
        }
    }
}
