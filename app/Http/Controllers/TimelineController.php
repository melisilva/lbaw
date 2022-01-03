<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Publicacao;
use App\Models\Comentario;
use App\Models\Gosto;
use DB;
use Illuminate\Support\Facades\Auth;

class TimelineController extends Controller {
    
    public function generalTimeline() { //Only used by Vis
        $posts=DB::table('publicacao')->orderBy('numerogostos','desc')->get();
        if(is_null($posts))
           return view('layouts.error204');
        return view('pages.feedvis',['posts'=>$posts]);
    }

    public function personalizedTimeline(){ //Used by Aut
        if(!Auth::check()) return redirect('/login');
        $id=Auth::id();
        if(DB::table('colega')->where('utilizador1','=',$id)->select('utilizador2')->exists()){
               $colleagues=DB::table('colega')->where('utilizador1','=',$id)->select('utilizador2')->get();
               foreach($colleagues as $friend){
            $posts=Publicacao::where('idutilizador',$friend->utilizador2)->get();
            return view('pages.feedaut_withposts',['posts'=>$posts]);
        }
        } 
        
        return  view('pages.feedaut_withnoposts');      
    }
}