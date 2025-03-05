<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use File;

class ProfileController extends Controller
{
    //vizualizar perfil
    public function index()
    {
        return view('admin.profile.index');
    }

    //atualizar perfil
    public function update(Request $request)
    {
        //dd($request->all()); --> var_dump do laravel
        $request->validate([
           'name' => ['required', 'max:100'],
           'email' => ['required', 'email', 'unique:users,email,' . Auth::user()->id],
           'image' => ['image', 'max:2048'],
        ]);

        $user = Auth::user();
        if($request->hasFile('image')){

            //Verifica se existe a imagem e apaga
            if(File::exists(public_path($user->image))){
                File::delete(public_path($user->image));
            }

            $image = $request->image;
            $imageName = rand() . '-Goku-' . $image->getClientOriginalName();
            $image->move(public_path('uploads'), $imageName);

            //Caminho da paste de imagens
            $path = "/uploads/" . $imageName;

            $user->image = $path;
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->back()->with('success' , 'Dados atualizados com sucesso!');
    }
}
