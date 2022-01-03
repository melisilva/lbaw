<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;

    protected $table= 'grupo';
    public $timestamps=true;
    public $fillable= ['nome', 'privacidade', 'tipogrupo'];
    
    public function members(){
        return $this->hasMany('App\Models\User');
    }

    public function pubs(){
        return $this->hasMany('App\Models\Publicacao');
    }
}
