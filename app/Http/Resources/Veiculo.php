<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Veiculo extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'marca' => $this->marca,
            'modelo' => $this->modelo,
            'cor' => $this->cor,
            'ano' => $this->ano,
            'placa' => $this->placa,
            'estado' => $this->estado,
            'preco' => $this->preco,
            'km' => $this->km,
            'transmissao' => $this->transmissao,
            'motor' => $this->motor,
            'observacoes' => $this->observacoes,
            'imagem' => $this->imagem,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'multas' => Multa::collection($this->whenLoaded('multas')),
        ];
    }
}
