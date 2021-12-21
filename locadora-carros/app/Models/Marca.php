<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;
    protected $fillable =['nome','imagem'];


    public function rules(){
        return [
        'nome'=>'required|unique:marcas,nome,'.$this->id.'',
        'imagem'=>'required|file|mimes:png,jpeg'
        ];
    }

    public function feedback(){
            return[
                'required'=>'O campo :attribute é obrigatório',
                'imagem.mimes'=>'O arquivo deve ter o formato PNG ou JPEG',
                'nome.unique'=>'Já existe uma marca com este nome cadastrada'
            ];
    }
}
