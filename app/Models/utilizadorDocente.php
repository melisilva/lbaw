<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class utilizadorDocente extends Model
{
    use HasFactory;
    
    protected $table= 'utilizadordocente';
    public $timestamps=false;
    public $fillable= ['departamento', 'formacao'];

    public function isAdmin(){ //Use this function to know if it's an Admin, if yes, we can use functions from that type of user
        if(DB::table('administrador')->('idDocente',$this->id)->exists())
        {
            return true;
        }
        else{
            return false;
        }
    }
}