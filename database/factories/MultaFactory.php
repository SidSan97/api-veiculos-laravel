<?php

namespace Database\Factories;

use App\Models\Multa;
use App\Models\Veiculo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Multa>
 */
class MultaFactory extends Factory
{
    protected $model = Multa::class;

    /**
     * @return array<string, mixed>
     */
    public function definition()
    {
        $cidades = [
            'São Paulo', 'Rio de Janeiro', 'Belo Horizonte', 'Curitiba', 'Porto Alegre',
            'Salvador', 'Recife', 'Fortaleza', 'Brasília', 'Manaus', 'Belém', 'Goiânia',
            'Campinas', 'São Luís', 'Maceió', 'Natal', 'João Pessoa', 'Teresina',
            'Florianópolis', 'Vitória', 'Campo Grande', 'Cuiabá', 'Aracaju', 'Macapá',
        ];

        $descricoes = [
            'Excesso de velocidade até 20% acima do limite',
            'Excesso de velocidade entre 20% e 50% acima do limite',
            'Avanço de sinal vermelho do semáforo',
            'Estacionamento em local proibido',
            'Uso de telefone celular ao volante',
            'Transitar em faixa ou pista regulamentada como exclusiva para ônibus',
            'Dirigir sem atenção ou sem os cuidados indispensáveis à segurança',
            'Conduzir veículo sem registro e licenciamento',
            'Não identificação do condutor infrator',
            'Transitar em velocidade superior à máxima permitida em até 20%',
            'Deixar de usar o cinto de segurança',
            'Transitar com faróis desligados em rodovia (período noturno ou condição de visibilidade reduzida)',
            'Dirigir sob influência de álcool (suspeita administrativa)',
            'Ultrapassagem em faixa contínua amarela',
            'Conversão ou retorno em local proibido pela sinalização',
        ];

        return [
            'descricao' => fake()->randomElement($descricoes),
            'valor' => fake()->randomFloat(2, 88.38, 1957.33),
            'data' => fake()->dateTimeBetween('-3 years', 'now'),
            'cidade' => fake()->randomElement($cidades),
            'veiculo_id' => Veiculo::factory(),
            'status' => fake()->randomElement(['pendente', 'paga', 'cancelada']),
            'observacoes' => fake()->optional(0.25)->randomElement([
                'Autuação por radar fixo.',
                'Blitz da PRF na BR-116.',
                'Agente da CET na avenida.',
                'Notificação por videomonitoramento.',
                'Defesa apresentada em prazo.',
            ]),
            'imagem' => null,
        ];
    }
}
