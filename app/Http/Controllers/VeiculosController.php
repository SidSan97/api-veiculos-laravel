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

        $veiculo->modelo = $request->input('modelo');
        $veiculo->cor    = $request->input('cor');
        $veiculo->ano    = $request->input('ano');
        $veiculo->placa  = $request->input('placa');

        if( $veiculo->save() ){
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
