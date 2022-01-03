<?php
//https://laravel.com/docs/8.x/controllers
//https://laracasts.com/discuss/channels/laravel/edit-user-profile-best-practice-in-laravel-55
//https://www.youtube.com/watch?v=376vZ1wNYPA&list=WL&index=96

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Publicacao;
use App\Models\utilizadorDocente;
use App\Models\utilizadorEstudante;
use App\Models\Moderador;

class UserController extends Controller
{
    /** @var  UserRepository */
    /**
     * Create a new controller instance.
     *
     * @param  \App\Repositories\UserRepository  $users
     * @return void
     */

    /**
     * Store a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->nome;
    }
    
    public function showProfiletoAdmin($id){
           $user = User::find($id);
           if(is_null($user))
             return view('layouts.error204');
            $posts=Publicacao::where('idutilizador',$id)->get();
            $profilePic = $user->fotoperfil;
            
            if(is_null($user->fotoheader)){
               $headerPic='assets/img/banner.jpg';   
              }
             else {
               $headerPic=$user->fotoheader;   
              }
            $instituicaoEnsino = $user->instituicaoensino;

            if(DB::table('utilizadorestudante')->where('id','=',$id)->exists()){
                $estudante=DB::table('utilizadorestudante')->where('id','=',$id)->get();
                foreach($estudante as $f){
                   $curso=$f->curso;
                   $anocorrente=$f->anocorrente;
                } 
                return view('pages.admin_profile_estudante', [
                    'user' => $user,
                    'posts'=>$posts,
                    'profilePic' => $profilePic,
                    'headerPic' => $headerPic,
                    'instituicaoEnsino'=>$instituicaoEnsino,
                    'curso'=>$curso,
                    'anoCorrente'=>$anocorrente
                ]);
            }
            
            if( DB::table('utilizadordocente')->where('id','=',$id)->exists()) {
                $docente=DB::table('utilizadordocente')->where('id','=',$id)->get();
                foreach($docente as $f){
                   $formacao=$f->formacao;
                   $departamento=$f->departamento;
                } 
                return view('pages.admin_profile_docente', [
                    'user' => $user,
                    'posts'=>$posts,
                    'profilePic' => $profilePic,
                    'headerPic' => $headerPic,
                    'instituicaoEnsino'=>$instituicaoEnsino,
                    'departamento'=>$departamento,
                    'formacao'=>$formacao
                ]);
            }

        
    }

    public function showProfile($id) 
    {
        $user = User::find($id);
        if(is_null($user))
           return view('layouts.error204');
        $colleagues=NULL;
        if(DB::table('colega')->where('utilizador1','=',$id)->select('utilizador2')->exists()){
            $colleagues=DB::table('colega')->where('utilizador1','=',$id)->select('utilizador2')->get();
        }

        if($user->privacidade == 'public' || Auth::check()){
            $posts=Publicacao::where('idutilizador',$id)->get();
            $profilePic = $user->fotoperfil;
            
            if(is_null($user->fotoheader)){
               $headerPic='assets/img/banner.jpg';   
              }
             else {
               $headerPic=$user->fotoheader;   
              }
            $instituicaoEnsino = $user->instituicaoensino;

            if(DB::table('utilizadorestudante')->where('id','=',$id)->exists()){
                $estudante=DB::table('utilizadorestudante')->where('id','=',$id)->get();
                foreach($estudante as $f){
                   $curso=$f->curso;
                   $anocorrente=$f->anocorrente;
                } 
                return view('pages.profile_estudante', [
                    'user' => $user,
                    'posts'=>$posts,
                    'profilePic' => $profilePic,
                    'headerPic' => $headerPic,
                    'instituicaoEnsino'=>$instituicaoEnsino,
                    'curso'=>$curso,
                    'anoCorrente'=>$anocorrente,
                    'colleagues'=>$colleagues
                ]);
            }
            
            if( DB::table('utilizadordocente')->where('id','=',$id)->exists()) {
                $docente=DB::table('utilizadordocente')->where('id','=',$id)->get();
                foreach($docente as $f){
                   $formacao=$f->formacao;
                   $departamento=$f->departamento;
                } 
                return view('pages.profile_docente', [
                    'user' => $user,
                    'posts'=>$posts,
                    'profilePic' => $profilePic,
                    'headerPic' => $headerPic,
                    'instituicaoEnsino'=>$instituicaoEnsino,
                    'departamento'=>$departamento,
                    'formacao'=>$formacao,
                    'colleagues'=>$colleagues
                ]);
            }

           
        }
        else{
        return view('layouts.error403');
        }
    }

    
    public function showUser($id){
        $user = User::find($id);
        if(is_null($user))
           return view('layouts.error204');
        return view('pages.users', ['user' => $user]);
    }
    
    public function config_view($id){
        if(!Auth::check()) return redirect('/login');
       $user = User::find($id);
       if(is_null($user))
           return view('layouts.error204');
       $profilePic = $user->fotoPerfil;
       $headerPic=$user->fotoHeader;

            if(DB::table('utilizadorestudante')->where('id','=',$id)->exists()){
                $estudante=DB::table('utilizadorestudante')->where('id','=',$id)->get();
                foreach($estudante as $f){
                   $curso=$f->curso;
                   $anocorrente=$f->anocorrente;
                } 
                return view('pages.config_estudante', [
                    'user' => $user,
                    'profilePic' => $profilePic,
                    'headerPic' => $headerPic,
                    'curso'=>$curso,
                    'anoCorrente'=>$anocorrente
                ]);
            }
            
            if( DB::table('utilizadordocente')->where('id','=',$id)->exists()) {
                $docente=DB::table('utilizadordocente')->where('id','=',$id)->get();
                foreach($docente as $f){
                   $formacao=$f->formacao;
                   $departamento=$f->departamento;
                } 
                return view('pages.config_docente', [
                    'user' => $user,
                    'profilePic' => $profilePic,
                    'headerPic' => $headerPic,
                    'departamento'=>$departamento,
                    'formacao'=>$formacao
                ]);
            }

           
    }
    
    public function config($id,Request $request){
         if(!Auth::check()) return redirect('/login');
        $user=User::find($id);
        if(is_null($user))
           return view('layouts.error204');
        if(!is_null($request['name'])){
            $user->nome=$request['name'];
        }
        if(!is_null($request['birthdate'])){
            $user->datanascimento=$request['birthdate'];
        }
        if(!is_null($request['birthdate'])){
            $user->datanascimento=$request['birthdate'];
        }
        if (array_key_exists('public', $request)) {
           $privacidade='public';
        }
        if (array_key_exists('private', $request)) {
           $privacidade='private';
        }
        if(DB::table('utilizadorestudante')->where('id','=',$id)->exists()){
           if(!is_null($request['course'])){
            DB::table('utilizadorestudante')->where('id',$id)->update([
                    'curso'=> $request->course]);
        }
           if(!is_null($request['year'])){
            DB::table('utilizadorestudante')->where('id',$id)->update([
                    'anocorrente'=> $request->year
                    ]);
        }
        }
        
        if(DB::table('utilizadordocente')->where('id','=',$id)->exists()){
           if(!is_null($request['formation'])){
            DB::table('utilizadordocente')->where('id',$id)->update([
                    'formacao'=> $request->formation]);
        }
           if(!is_null($request['department'])){
            DB::table('utilizadordocente')->where('id',$id)->update([
                    'departamento'=> $request->department
                    ]);
        }
        }
        
        $user->save();
           
        return redirect()->intended('genfeed')->withSuccess('Update complete');
    }

    //Delete user
    public function delete(Request $request, $id){
        if(!Auth::check()) return redirect('/login');
        $user = User::find($id);
        if(is_null($user))
           return view('layouts.error204');
        $user->delete(); //Don't have to do it directly on utilizadorEstudante and utilizadorDocente because of the DELETE ON CASCADE
        return redirect()->intended('login');
    } 
    
    public function choosePath(){
       $user=User::find(Auth::id());
       if(is_null($user))
           return view('layouts.error204');
       return view('pages.choose_path', [
                    'user' => $user
                ]);
    }
    
    public function createDocente(Request $request){
       $user=Auth::id();
       if(is_null($user))
           return view('layouts.error204');
       $formacao=$request['formation'];
       $departamento=$request['department'];
       DB::table('utilizadordocente')->insert([
                'id'=>$user,
                'departamento'=>$departamento,
                'formacao'=>$formacao
            ]);
       return redirect()->intended('genfeed');
    }
    
    public function createEstudante(Request $request){
       $user=Auth::id();
       if(is_null($user))
           return view('layouts.error204');
       $curso=$request['course'];
       $ano=$request['year'];
       $media=$request['media'];
       DB::table('utilizadorestudante')->insert([
                'id'=>$user,
                'curso'=>$curso,
                'media'=>$media,
                'anocorrente'=>$ano
            ]);
       return redirect()->intended('genfeed');
    }
}