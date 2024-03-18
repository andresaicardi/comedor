<?php

namespace App\Http\Controllers;

use App\Models\permisos;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {

        Auth::logout();

        $redirect_to=Session::get('redirect_to');
        /*Elimino las sesiones de PHP*/
        Session::flush();

        $validator =  $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
   
    
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            
            
            $clientes = Auth::user();
            // dd($clientes);

            $query=permisos::where('permisos.user_id',$clientes->id)
                    ->join('rol', 'permisos.rol_id', '=', 'rol.id')
                    ->join('sector', 'rol.sector_id', '=', 'sector.id')
                    ->join('users','permisos.user_id','=','users.id')
                    ->select('rol.id AS rol_id', 'rol.nombre AS rol', 'sector.id AS sector_id', 'sector.nombre AS sector','users.email','users.id')->first();

            $partes = explode("@", $query->email);
            $login = $partes[0];
            $login_formateado = ucwords($login);
            $primera_letra = substr($login_formateado, 0, 1);
            $resto_nombre = ucwords(substr($login_formateado, 1));
            $nombre =$primera_letra.' '.$resto_nombre;
            
            Auth::loginUsingId($clientes->id);
            Session::put(['userLogin'=>True]);
            Session::put(['sector'=>$query->sector]);
            Session::put(['rol'=>$query->rol]);
            Session::put(['id'=>$clientes->id]);


            if($query->rol!='Colaborador'){
                Session::put(['nameUser'=>$nombre]);
            }else{
                Session::put(['nameUser'=>$query->rol]);
            }
            
            return redirect()->intended('dashboard')
                        ->withSuccess('Signed in');


        }
        $validator['emailPassword'] = 'Email address or password is incorrect.';
        return redirect("login")->withErrors($validator);
    }



    public function registration()
    {
        return view('auth.registration');
    }

    public function customRegistration(Request $request)
    {  
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
           
        $data = $request->all();
        $check = $this->create($data);
         
        return redirect("dashboard")->withSuccess('You have signed-in');
    }


    public function create(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password'])
      ]);
    }

    public function dashboard()
    {
        if(Auth::check()){
            return view('home');
        }
  
        return redirect("login")->withSuccess('You are not allowed to access');
    }

    public function signOut() {
        Session::flush();
        Auth::logout();
  
        return Redirect('/');
    }
}