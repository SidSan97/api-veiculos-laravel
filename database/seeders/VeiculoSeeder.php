<?php

namespace Database\Seeders;

use App\Models\Veiculo;
use Illuminate\Database\Seeder;

class VeiculoSeeder extends Seeder
{
    /**
     * Gera 100 veículos com dados típicos do mercado brasileiro.
     */
    public function run(): void
    {
        Veiculo::query()->delete();

        Veiculo::factory()
            ->count(100)
            ->create();
    }
}
