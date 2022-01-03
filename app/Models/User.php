<?php

//https://matthewdaly.co.uk/blog/2017/12/02/full-text-search-with-laravel-and-postgresql/

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;

    const CREATED_AT = 'datatemporegisto';
    const UPDATED_AT = 'atualizado';

    protected $table= 'users';
    
    public $timestamps=true;
    public $fillable= ['nome', 'email', 'password', 'datanascimento', 'instituicaoensino', 'privacidade','fotoperfil'];
    
    protected $hidden = [
        'password', 'remember_token'
    ];

    public function groups(){
        return $this->belongsToMany('App\Models\Grupo', 'grupoutilizador');
    }
    
    public function pubs(){
        return $this->hasMany('App\Models\Publicacao');
    }

    public function com(){
        return $this->hasMany('App\Models\Comentario');
    }

    public function likes(){
        return $this->hasMany('App\Models\Gosto');
    }

    public function nots(){
        return $this->hasMany('App\Models\Notificacao');
    }

    public function following(){
        return $this->belongsToMany('App\Models\User','friend','utilizador1','utilizador2');
    }

    public function followers(){
        return $this->belongsToMany('App\Models\User','friend','utilizador2','utilizador1');
    }

    public function getProfilePic() {
        if (is_null($this->fotoPerfil))
            return "assets/img/avatar.jpg"; //need to fix name or maybe not, depending on what we write on the views
        return $this->fotoPerfil;
    }

    public function getHeaderPic() {
        if (is_null($this->fotoHeader))
            return "assets/img/banner.jpg"; //need to fix name or maybe not, depending on what we write on the views
        return $this->fotoHeader;
    }   
}