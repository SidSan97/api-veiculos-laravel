<?php

namespace Database\Factories;

use App\Models\Veiculo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Veiculo>
 */
class VeiculoFactory extends Factory
{
    protected $model = Veiculo::class;

    /**
     * @return array<string, mixed>
     */
    public function definition()
    {
        [$marca, $modelo] = $this->marcaEModelo();

        $ano = fake()->numberBetween(2015, (int) date('Y'));
        $estado = fake()->randomElement(['novo', 'semi-novo']);
        $km = $estado === 'novo'
            ? fake()->randomFloat(1, 0, 1500)
            : fake()->randomFloat(1, 5000, 185000);

        $precoBase = fake()->numberBetween(28000, 185000);
        $preco = $estado === 'novo'
            ? $precoBase * fake()->randomFloat(2, 1.02, 1.12)
            : $precoBase * fake()->randomFloat(2, 0.55, 0.95);

        return [
            'marca' => $marca,
            'modelo' => $modelo,
            'cor' => fake()->randomElement([
                'Branco', 'Preto', 'Prata', 'Cinza', 'Vermelho', 'Azul', 'Verde',
                'Marrom', 'Bege', 'Amarelo', 'Laranja', 'Vinho',
            ]),
            'ano' => $ano,
            'placa' => strtoupper(fake()->unique()->bothify('???#?##')),
            'estado' => $estado,
            'preco' => round($preco, 2),
            'km' => $km,
            'transmissao' => fake()->randomElement(['Manual', 'Automática', 'CVT', 'Automática de dupla embreagem']),
            'motor' => fake()->randomElement([
                '1.0 12V flex', '1.3 8V flex', '1.4 8V flex', '1.5 16V flex', '1.6 16V flex',
                '2.0 16V flex', '2.0 16V turbo flex', '2.4 16V flex', '3.0 V6 flex',
            ]),
            'observacoes' => fake()->optional(0.35)->randomElement([
                'Único dono, revisões em concessionária.',
                'Pequenos detalhes de pintura no para-choque.',
                'Kit multimídia original, pneus novos.',
                'Aceita financiamento e troca.',
                'Veículo de leilão com laudo aprovado.',
            ]),
            'imagem' => null,
        ];
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function marcaEModelo(): array
    {
        $mapa = [
            'Fiat' => ['Argo', 'Cronos', 'Mobi', 'Pulse', 'Fastback', 'Toro', 'Strada'],
            'Volkswagen' => ['Polo', 'Virtus', 'T-Cross', 'Nivus', 'Taos', 'Jetta', 'Amarok'],
            'Chevrolet' => ['Onix', 'Onix Plus', 'Tracker', 'Spin', 'S10', 'Montana', 'Cruze'],
            'Ford' => ['Ka', 'Ka Sedan', 'EcoSport', 'Territory', 'Ranger', 'Maverick'],
            'Hyundai' => ['HB20', 'HB20S', 'Creta', 'Tucson', 'Santa Fe', 'i30'],
            'Honda' => ['City', 'Civic', 'HR-V', 'CR-V', 'Fit'],
            'Toyota' => ['Yaris', 'Corolla', 'Corolla Cross', 'Hilux', 'SW4', 'RAV4'],
            'Renault' => ['Kwid', 'Sandero', 'Logan', 'Duster', 'Oroch', 'Captur'],
            'Jeep' => ['Renegade', 'Compass', 'Commander', 'Wrangler'],
            'Nissan' => ['March', 'Versa', 'Kicks', 'Frontier', 'Leaf'],
            'Peugeot' => ['208', '2008', '3008', '408'],
            'Citroën' => ['C3', 'C4 Cactus', 'Aircross'],
            'Mitsubishi' => ['L200 Triton', 'ASX', 'Outlander', 'Eclipse Cross'],
            'BMW' => ['Serie 1', 'Serie 3', 'X1', 'X3'],
            'Mercedes-Benz' => ['Classe A', 'Classe C', 'GLA', 'GLC'],
        ];

        $marca = fake()->randomElement(array_keys($mapa));
        $modelo = fake()->randomElement($mapa[$marca]);

        return [$marca, $modelo];
    }
}
