<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function usuarios()
    {
        $usuarios = DB::table('usuarios')
            ->join('rol', 'usuarios.id_rol', '=', 'rol.id')
            ->select('usuarios.*', 'rol.nombre as nombre_rol')
            ->get();
        return view('usuarios.index', compact('usuarios'));
    }

    public function usuarios_crear(Request $request)
    {
        try {
            if($request->id_user == ''){
                $sql = "INSERT INTO usuarios (id_rol, nombre, apellido, correo, contrasena, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
                DB::insert($sql, [$request->roles, $request->nombre, $request->apellido, $request->correo, $request->contrasena]);
            }else{
                DB::table('usuarios')
                ->where('id', $request->id_user)
                ->update([
                    'id_rol' => $request->roles,
                    'nombre' => $request->nombre,
                    'apellido' => $request->apellido,
                    'correo' => $request->correo,
                    'contrasena' => $request->contrasena,
                    'updated_at' => Carbon::now()->timestamp,
                ]);
            }
            
            return redirect()->route('usuarios')->with('ok', 'ok');
        } catch (\Exception $e) {
            return redirect()->route('usuarios')->with('nook', 'nook');
        }
    }

    public function usuarios_detalles(Request $request)
    {
        $id_user = $request->id;
        $usuario = DB::table('usuarios')->where('id', '=', $id_user)->get()->last();
        return $usuario;
    }

    public function usuarios_verificar(Request $request)
    {
        $correo = $request->correo;
        $usuario = DB::table('usuarios')->where('correo', '=', $correo)->get()->last();
        if($usuario){
            return response()->json('found');
        }else{
            return response()->json('nofound');
        }
    }
    
    public function perfil()
    {
        return view('usuarios.perfil');
    }
}
