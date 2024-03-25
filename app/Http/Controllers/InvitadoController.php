<?php

namespace App\Http\Controllers;

use App\Models\colab_menu;
use App\Models\colaborador;
use App\Models\menu_dia;
use App\Models\valoracion_mensual;
use App\Models\valoracion_semanal;
use App\Http\Controllers\Controller;
use DateTime;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class InvitadoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        $this->middleware('guest')->except('index', 'puntuacion', 'loginInvitado', 'getMenu', 'getValorarMes', 'postEnvio', 'postPuntuacion', 'postMensual');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('colaborador');
    }

    public function puntuacion()
    {
        return view('puntuacion');
    }

    public function loginInvitado(Request $request)
    {

        $arr=explode("-",$request->input('qr'));

        $legajo = $arr[0];
        $password = $arr[1];

        $login=colaborador::where('legajo','=',$legajo)->first();
        if($login==null){
            return response()->json([
                'message'=>'No se encontro el usuario, el legajo no existe o no se encuentra'
            ]);
        }
        if(strcmp(trim($login->password),trim($password)) !== 0){
            return response()->json([
                'message'=>'La contraseña no es correcta'
            ]);
        }
        
        return response()->json([
            'message'=>'Bienvenido al Menú semana!',
            'login'=>$login
        ]);
    }

    public function getMenu($legajo)
    {
        $colab=colaborador::where('legajo','=',$legajo)->first();

        $existePedido=DB::select("SELECT * 
        FROM colab_menu
        WHERE id_colab='$colab->id_colaborador' AND WEEK(modified)=WEEK(NOW());");

        if(count($existePedido)>0){
            return response()->json([
                'message'=>'Ya se relizo el pedido!'
            ]);
        }

        $diaSemana = date("l");

        if ($diaSemana != "Monday" && $diaSemana != "Tuesday" && $diaSemana != "Wednesday" && $diaSemana != "Thursday") {
            return response()->json([
                'message'=>'El periodo de elegir el Menu esta cerrado'
            ]);
        }

        $menu_semanal=DB::select('SELECT md.id_menu_dia, md.fecha,
        CASE DAYOFWEEK(md.fecha)
        WHEN 2 THEN "Lunes"
        WHEN 3 THEN "Martes"
        WHEN 4 THEN "Miércoles"
        WHEN 5 THEN "Jueves"
        WHEN 6 THEN "Viernes"
        END AS dia_semana,
        md.id_menu1, m1.menu_description AS menu1, md.id_menu2, m2.menu_description AS menu2, md.id_menu3, m3.menu_description AS menu3,
        md.id_postre1, p1.postre_description AS postre1, md.id_postre2, p2.postre_description AS postre2, md.id_postre3, p3.postre_description AS postre3
        FROM menu_dia md
        INNER JOIN menu m1 ON md.id_menu1=m1.id_menu
        INNER JOIN menu m2 ON md.id_menu2=m2.id_menu
        INNER JOIN menu m3 ON md.id_menu3=m3.id_menu
        INNER JOIN postre p1 ON md.id_postre1=p1.id_postre
        INNER JOIN postre p2 ON md.id_postre2=p2.id_postre
        INNER JOIN postre p3 ON md.id_postre3=p3.id_postre
        WHERE WEEK(md.fecha)=WEEK(NOW())+1
        ORDER BY md.fecha');
        return response()->json($menu_semanal);
    }

    public function postEnvio(Request $request)
    {

        $selectedMenus=$request->input('selectedMenus');
        $selectedPostres=$request->input('selectedPostres');
        $selectedPan=$request->input('selectedPan');
        $legajo=$request->input('legajo');
        $password=$request->input('password');
        $hora='00:00';


        foreach ($selectedMenus as $key => $value) {
            $menu_dia=menu_dia::find($key);
            $colab=colaborador::where('legajo','=',$legajo)->where('password','=',$password)->first();
            $hora=$colab->hora;
            $colab_menu= new colab_menu();
            $colab_menu->id_menu_dia=$key;
            $colab_menu->id_colab=$colab->id_colaborador;
            $colab_menu->id_menu=$value;
            $colab_menu->id_postre=$selectedPostres[$key];
            $colab_menu->pan=array_key_exists($key, $selectedPan) ? 1 : 0;
            $colab_menu->hora=$hora;
            $colab_menu->fecha=$menu_dia->fecha;
            $colab_menu->save();
        }

        return response()->json([
            'message'=>'Se a realizado el pedido con exito!'
        ]);
    }

    public function postPuntuacion(Request $request)
    {
        $now = date('Y-m-d');
        $colab=colaborador::where('legajo','=',$request->input('legajo'))->first();
        $nuevaPuntuacion= new valoracion_semanal();
        $nuevaPuntuacion->id_colab=$colab->id_colaborador;
        $nuevaPuntuacion->valoracion=$request->input('puntuacion');
        $nuevaPuntuacion->comentario=$request->input('comentario');
        $nuevaPuntuacion->fecha=$now;
        $nuevaPuntuacion->save();

        return response()->json([
            'message'=>'Se realizo la valoracion con exito!'
        ]);
    }

    public function postMensual(Request $request)
    {
        $now = date('Y-m-d');
        $colab=colaborador::where('legajo','=',$request->input('legajo'))->first();
        $nuevaPuntuacion= new valoracion_mensual();
        $nuevaPuntuacion->id_colab=$colab->id_colaborador;
        $nuevaPuntuacion->fecha=$now;
        $nuevaPuntuacion->pregunta1=$request->input('pregunta1');
        $nuevaPuntuacion->pregunta2=$request->input('pregunta2');
        $nuevaPuntuacion->pregunta3=$request->input('pregunta3');
        $nuevaPuntuacion->pregunta4=$request->input('pregunta4');
        $nuevaPuntuacion->save();

        $puntuacion_hoy=DB::select("SELECT * FROM valoracion_semanal WHERE id_colab='$colab->id_colaborador' AND fecha=DATE(NOW())");

        if(count($puntuacion_hoy)==0){
            return response()->json([
                'message'=>'Realizar puntuacion diaria!'
            ]);
        }

        return response()->json([
            'message'=>'Se realizo la valoracion con exito!'
        ]);
    }

    public function getValorarMes($id){
        $pedidos=DB::select("SELECT * FROM colab_menu WHERE id_colab='$id' AND MONTH(fecha) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(fecha) = YEAR(CURDATE() - INTERVAL 1 MONTH)");
        
        if(count($pedidos)>0){
            $existePuntuacion=DB::select("SELECT * FROM valoracion_mensual WHERE id_colab='$id' AND MONTH(fecha)=MONTH(NOW())");

            if(count($existePuntuacion)==0){
                return response()->json([
                    'message'=>'Realizar puntuacion mensual!'
                ]);
            } 
        }

        $puntuacion_hoy=DB::select("SELECT * FROM valoracion_semanal WHERE id_colab='$id' AND fecha=DATE(NOW())");

        if(count($puntuacion_hoy)==0){
            return response()->json([
                'message'=>'Realizar puntuacion diaria!'
            ]);
        }

        return response()->json([
            'message'=>'Ya se realizo la puntuacion diaria!'
        ]);

    }


}
