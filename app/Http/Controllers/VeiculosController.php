<?php

namespace App\Http\Controllers;

use App\Models\Veiculo as ModelsVeiculo;
use App\Http\Resources\Veiculo as VeiculoResource;
use Illuminate\Http\Request;

class VeiculosController extends Controller
{
    public function index()
    {
        return VeiculoResource::collection(
            ModelsVeiculo::with('multas')->get()
        );
    }

    public function store(Request $request)
    {
        $veiculo = new ModelsVeiculo();

        $veiculo->marca        = $request->input('marca');
        $veiculo->modelo       = $request->input('modelo');
        $veiculo->cor          = $request->input('cor');
        $veiculo->ano          = $request->input('ano');
        $veiculo->placa        = $request->input('placa');
        $veiculo->estado       = $request->input('estado');
        $veiculo->preco        = $request->input('preco');
        $veiculo->km           = $request->input('km');
        $veiculo->transmissao  = $request->input('transmissao');
        $veiculo->motor        = $request->input('motor');
        $veiculo->observacoes  = $request->input('observacoes');

        // Upload da imagem do veículo em public/carros/{modelo}/
        if ($request->hasFile('imagem') && $request->file('imagem')->isValid()) {
            $pastaModelo = str_replace(' ', '-', trim($veiculo->modelo ?? 'outros'));
            $dir = public_path('carros' . DIRECTORY_SEPARATOR . $pastaModelo);

            if (! is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            $file = $request->file('imagem');
            $nomeArquivo = basename($file->getClientOriginalName());
            $nomeArquivo = uniqid() . '_' . $nomeArquivo; // Evita conflitos de nome
            $file->move($dir, $nomeArquivo);

            $veiculo->imagem = 'carros/' . $pastaModelo . '/' . $nomeArquivo;
        }

        if ($veiculo->save()) {
            $this->syncMultas($veiculo, $request);
            return new VeiculoResource($veiculo->load('multas'));
        }
    }

    public function show($param, $valor)
    {
        $veiculos = ModelsVeiculo::with('multas')->where($param, $valor)->get();

        if ($veiculos->isEmpty()) {
            return response()->json([
                'message' => 'nenhuma placa correponde ao parâmetro informado',
            ], 404);
        }

        return VeiculoResource::collection($veiculos);
    }

    public function update(Request $request, $id)
    {
        $veiculo = ModelsVeiculo::findOrFail($id);

        $veiculo->marca        = $request->input('marca');
        $veiculo->modelo       = $request->input('modelo');
        $veiculo->cor          = $request->input('cor');
        $veiculo->ano          = $request->input('ano');
        $veiculo->placa        = $request->input('placa');
        $veiculo->estado       = $request->input('estado');
        $veiculo->preco        = $request->input('preco');
        $veiculo->km           = $request->input('km');
        $veiculo->transmissao  = $request->input('transmissao');
        $veiculo->motor        = $request->input('motor');
        $veiculo->observacoes  = $request->input('observacoes');

        if ($request->hasFile('imagem') && $request->file('imagem')->isValid()) {
            $pastaModelo = str_replace(' ', '-', trim($veiculo->modelo ?? 'outros'));
            $dir = public_path('carros' . DIRECTORY_SEPARATOR . $pastaModelo);

            if (! is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            $file = $request->file('imagem');
            $nomeArquivo = basename($file->getClientOriginalName());
            $nomeArquivo = uniqid() . '_' . $nomeArquivo;
            $file->move($dir, $nomeArquivo);

            $veiculo->imagem = 'carros/' . $pastaModelo . '/' . $nomeArquivo;
        }

        if ($veiculo->save()) {
            $this->syncMultas($veiculo, $request);
            return new VeiculoResource($veiculo->load('multas'));
        }
    }

    private function syncMultas(ModelsVeiculo $veiculo, Request $request): void
    {
        $multas = $request->input('multas', []);
        if (! is_array($multas) || count($multas) === 0) {
            return;
        }

        foreach ($multas as $index => $multaData) {
            if (! is_array($multaData)) {
                continue;
            }

            $multaImagem = $this->resolveMultaImagem($request, $index);
            $attributes = [
                'descricao'   => $multaData['descricao'] ?? null,
                'valor'       => $multaData['valor'] ?? null,
                'data'        => $multaData['data'] ?? null,
                'cidade'      => $multaData['cidade'] ?? null,
                'status'      => $multaData['status'] ?? null,
                'observacoes' => $multaData['observacoes'] ?? null,
            ];

            if ($multaImagem !== null) {
                $attributes['imagem'] = $multaImagem;
            } elseif (array_key_exists('imagem', $multaData)) {
                $attributes['imagem'] = $multaData['imagem'];
            }

            if (! empty($multaData['id'])) {
                $multa = $veiculo->multas()->where('id', $multaData['id'])->first();
                if ($multa) {
                    $multa->update($attributes);
                    continue;
                }
            }

            $veiculo->multas()->create($attributes);
        }
    }

    private function resolveMultaImagem(Request $request, int $index): ?string
    {
        if (! $request->hasFile('multas') || ! is_array($request->file('multas'))) {
            return null;
        }

        $files = $request->file('multas');
        if (! isset($files[$index]['imagem'])) {
            return null;
        }

        $file = $files[$index]['imagem'];
        if (! $file || ! $file->isValid()) {
            return null;
        }

        $multaImagemPath = $file->store('multas', 'public');

        return url('storage/' . $multaImagemPath);
    }

    public function destroy($id)
    {
        $veiculo = ModelsVeiculo::with('multas')->findOrFail($id);

        if ($veiculo->delete()) {
            return new VeiculoResource($veiculo);
        }
    }
}
