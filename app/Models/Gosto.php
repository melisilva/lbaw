<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gosto extends Model
{
    use HasFactory;

    const CREATED_AT = 'datatempocriacao';
    const UPDATED_AT = 'atualizado';

    protected $table= 'gosto';
    public $timestamps=true;
    
    public function notLike(){
        return $this->hasOne('App\Models\Notificacao');
    }

    public function userLike(){
        return $this->belongsTo('App\User');
    }

    public function pubLike(){
        return $this->belongsTo('App\Models\Publicacao');
    }
}