<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Multa extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'descricao' => $this->descricao,
            'valor' => $this->valor,
            'data' => $this->data,
            'cidade' => $this->cidade,
            'veiculo_id' => $this->veiculo_id,
            'status' => $this->status,
            'observacoes' => $this->observacoes,
            'imagem' => $this->imagem,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
