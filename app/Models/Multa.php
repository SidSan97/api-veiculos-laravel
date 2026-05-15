<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Multa extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'descricao',
        'valor',
        'data',
        'cidade',
        'veiculo_id',
        'status',
        'observacoes',
        'imagem',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'valor' => 'decimal:2',
        'data' => 'datetime',
    ];

    public function veiculo(): BelongsTo
    {
        return $this->belongsTo(Veiculo::class);
    }
}
