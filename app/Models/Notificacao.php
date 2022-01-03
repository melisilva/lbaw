<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacao extends Model
{
    use HasFactory;

    const CREATED_AT = 'datatempo';
    const UPDATED_AT = 'atualizado';

    protected $table= 'notificacao';
    public $timestamps=true;
    public $fillable= ['tipo'];
    

    public function pub(){
        return $this->belongsTo('App\Models\Publicacao');
    }

    public function com(){
        return $this->belongsTo('App\Models\Comentario');
    }

    public function like(){
        return $this->belongsTo('App\Models\Gosto');
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}