<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{
public function __construct(Marca $marca){
        $this->marca = $marca;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $marca = $this->marca->all();

        return response()->json($marca,200);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate($this->marca->rules(),$this->marca->feedback());

        $image = $request->file('imagem');
        $imagem_urn =  $image->store('imagens/marcas','public');

        $marca = $this->marca->create([
            'nome'=>$request->nome,
            'imagem'=>$imagem_urn
        ]);

        return response()->json($marca,200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $marca = $this->marca->find($id);

        if($marca===null){
       return response()->json(['erro'=>'nehuma registro encontrado'],404);
        }
        return response()->json($marca,200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $marca=$this->marca->find($id);
        if($marca===null){
            return response()->json(['erro'=>'Registro não encontrado para realizar atualização'],404);
        }

        if($request->method()==='PATCH'){
            $regrasDinamicas=array();
            foreach($marca->rules() as $input => $regras){

                if(array_key_exists($input, $request->all())){
                    $regrasDinamicas[$input]=$regras;
                }
            }
            $request->validate($regrasDinamicas, $marca->feedback());
        }else{
            $request->validate($marca->rules(),$marca->feedback());
        }

        if($request->file('imagem')){
            Storage::disk('public')->delete($marca->imagem);
        }

        $image = $request->file('imagem');
        $imagem_urn =  $image->store('imagens/marcas','public');

        $marca->update([
            'nome'=>$request->nome,
            'imagem'=>$imagem_urn
        ]);



        return response()->json($marca,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $marca=$this->marca->find($id);
        if($marca===null){
            return response()->json(['erro'=>'Nehum registro encontrado para remoção'],404);
        }


        Storage::disk('public')->delete($marca->imagem);

        $marca->delete();
        return response()->json(['msg'=>'Marca removida com sucesso'],200);
    }
}
