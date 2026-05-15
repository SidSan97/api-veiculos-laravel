<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Veiculo extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'marca',
        'modelo',
        'cor',
        'ano',
        'placa',
        'estado',
        'preco',
        'km',
        'transmissao',
        'motor',
        'observacoes',
        'imagem',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'ano' => 'integer',
        'preco' => 'decimal:2',
        'km' => 'float',
    ];

    public function multas(): HasMany
    {
        return $this->hasMany(Multa::class);
    }
}
