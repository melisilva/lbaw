<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GruposController extends Controller 
{

 public function deleteGroup($id)
    {
        if(!Auth::check()) return redirect('/login');
        $group = Grupo::find($id);

        $this->authorize('delete', $group);

        $group->delete();
        return redirect()->route('grupos');
    }

    public function showNewGroup($id) {
        $group = Grupo::find($id);
        if (empty($group)) {
            return redirect('/');
        }

        return view('pages.new_group');
    }

    public function addNewGroup(Request $request) {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $data = $request->all();


        $code = join('-', explode(' ', strtolower($data['name'])));

        Grupo::create([
            'nome' => $data['nome'],
            'privacidade' => $request->has('private'),
            'tipo' => $data['tipo'],
            'dataTempoCriacao' => $request->headers["Date"],
        ]);

        return redirect('/grupos/' . $code);
    }
}
