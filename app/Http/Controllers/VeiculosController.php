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

    public function create()
    {
        //
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

        if( $veiculo->save() ){
            // Cadastro de multas, se houver
            $multas = $request->input('multas', []);
            if (is_array($multas) && count($multas) > 0) {
                foreach ($multas as $multaData) {
                    $multaImagem = null;
                    // Upload da imagem da multa (se enviada via multipart)
                    if ($request->hasFile('multas') && is_array($request->file('multas'))) {
                        $index = array_search($multaData, $multas, true);
                        if ($index !== false && isset($request->file('multas')[$index]['imagem'])) {
                            $file = $request->file('multas')[$index]['imagem'];
                            if ($file && $file->isValid()) {
                                $multaImagemPath = $file->store('multas', 'public');
                                $multaImagem = url('storage/' . $multaImagemPath);
                            }
                        }
                    }
                    $veiculo->multas()->create([
                        'descricao'    => $multaData['descricao'] ?? null,
                        'valor'        => $multaData['valor'] ?? null,
                        'data'         => $multaData['data'] ?? null,
                        'cidade'       => $multaData['cidade'] ?? null,
                        'status'       => $multaData['status'] ?? null,
                        'observacoes'  => $multaData['observacoes'] ?? null,
                        'imagem'       => $multaImagem ?? ($multaData['imagem'] ?? null),
                    ]);
                }
            }
            return new VeiculoResource( $veiculo->load('multas') );
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

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $veiculo = ModelsVeiculo::findOrFail($request->id);

        $veiculo->modelo = $request->input('modelo');
        $veiculo->cor    = $request->input('cor');
        $veiculo->ano    = $request->input('ano');
        $veiculo->placa  = $request->input('placa');

        if( $veiculo->save() ){
            return new VeiculoResource( $veiculo->load('multas') );
          }
    }

    public function destroy($id)
    {
        $veiculo = ModelsVeiculo::with('multas')->findOrFail($id);

        if ($veiculo->delete()) {
            return new VeiculoResource($veiculo);
        }
    }
}
