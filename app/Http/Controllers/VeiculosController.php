<?php

namespace App\Http\Controllers;

use App\Models\Veiculo as ModelsVeiculo;
use App\Http\Resources\Veiculo as VeiculoResource;
use Illuminate\Http\Request;

class VeiculosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $veiculo = ModelsVeiculo::paginate(15);
        return VeiculoResource::colection($veiculo);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $veiculo = new ModelsVeiculo();

        $veiculo->modelo = $request->input('modelo');
        $veiculo->cor    = $request->input('cor');
        $veiculo->ano    = $request->input('ano');
        $veiculo->placa  = $request->input('placa');

        if( $veiculo->save() ){
            return new VeiculoResource( $veiculo );
          }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $veiculo = ModelsVeiculo::findOrFail($request->id);

        $veiculo->modelo = $request->input('modelo');
        $veiculo->cor    = $request->input('cor');
        $veiculo->ano    = $request->input('ano');
        $veiculo->placa  = $request->input('placa');

        if( $veiculo->save() ){
            return new VeiculoResource( $veiculo );
          }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $veiculo = ModelsVeiculo::findOrFail($id);

        if( $veiculo->delete() ){
            return new VeiculoResource( $veiculo );
          }
    }
}
