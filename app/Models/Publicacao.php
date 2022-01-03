<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Publicacao extends Model
{
    use HasFactory;

    const CREATED_AT = 'datatempo';
    const UPDATED_AT = 'atualizado';

    protected $table= 'publicacao';
    public $timestamps=true;
    public $fillable= ['idutilizador','conteudo', 'anexo', 'tipoanexo'];

    public function notPub(){
        return $this->hasOne('App\Models\Notificacao');
    }

    public function userPub(){
        return $this->belongsTo('App\Models\User');
    }

    public function com(){
        return $this->hasMany('App\Models\Comentario');
    }

    public function likes(){
        return $this->hasMany('App\Models\Gosto');
    }

    public function groupPub(){
        return $this->belongsTo('App\Models\Grupo');
    }

    public function getUserName($id){
        return BD::table('utilizador')->select('nome')->where('id','=',$id)->get();
    }

    public function getAnexo() {
        if (is_null($this->anexo))
            return NULL; //need to fix name or maybe not, depending on what we write on the views
        return $this->anexo;
    }
  
    public function getCorpo() {
        if (is_null($this->conteudo))
            return NULL;
        return $this->conteudo;
    }
}
