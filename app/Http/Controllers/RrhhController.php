<?php

namespace App\Http\Controllers;

use App\Models\colaborador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RrhhController extends Controller
{

    public function generarQr($qr,$nombreCompleto){

        return view('rrhh.generarQr')->with(compact('qr','nombreCompleto'));
    }

    public function mesColaborador(){
        return view('rrhh.mesColaborador');
    }

    public function editarColaborador(){
        return view('rrhh.editarColaborador');
    }
    
    public function crearColaborador(){
        return view('rrhh.crearColaborador');
    }

    public function postCrearColaborador(Request $request){

        $existe=DB::select("SELECT * FROM colaborador WHERE legajo=".$request->input("legajo"));

        if(count($existe)>0){
            return response()->json([
                'message'=>'Ya existe un colobarador con el mismo Legajo'
            ]);
        }

        $nuevo_colab= new colaborador();
        $nuevo_colab->legajo=$request->input('legajo');
        $nuevo_colab->cedula=$request->input('cedula');
        $nuevo_colab->password=$request->input('cedula');
        $nuevo_colab->nombre=$request->input('nombre');
        $nuevo_colab->apellido=$request->input('apellido');
        $nuevo_colab->hora=$request->input('hora');
        $nuevo_colab->save();

        return response()->json([
            'message'=>'Se creo el colaborador con exito!',
            'qr' => $nuevo_colab->legajo=$request->input('legajo').'-'.$request->input('cedula'),
            'nombreCompleto' => $request->input('nombre').' '.$request->input('apellido')
        ]);
    }

    public function showPuntuacion(){
        return view('rrhh.showPuntuacion');
    }

    public function showPuntuacionMensual(){
        return view('rrhh.showPuntuacionMensual');
    }

    public function getPuntuacion($fecha){
        $allPuntuacion=DB::select("SELECT vs.*, c.cedula, CONCAT(c.nombre,' ',c.apellido) AS nombre, vs.fecha
        FROM valoracion_semanal vs
        INNER JOIN colaborador c ON vs.id_colab=c.id_colaborador
        WHERE vs.fecha='$fecha'
        ORDER BY c.cedula desc");

        if(count($allPuntuacion)==0){
            return response()->json([
                'message'=>'No se encontro ningun dato expecifico de esta semana.'
            ]);
        }

        $puntos=0;
        $cantidad=0;
        foreach ($allPuntuacion as $value) {
            $puntos+=$value->valoracion;
            $cantidad++;
        }
        $totalGeneral=$puntos/$cantidad;

        return response()->json([
            'message'=>'Exito!',
            'puntos'=>$totalGeneral,
            'allPuntuacion'=>$allPuntuacion
        ]);
        
    }

    public function getColaborador($legajo){
        $colaborador = colaborador::where('legajo','=',$legajo)->first();

        if($colaborador===null){
            return response()->json([
                'message'=>'No se encontro ningun dato expecifico del colaborador',
            ]);
        }

        return response()->json([
            'message'=>'Exito!',
            'colaborador'=>$colaborador,
        ]);
    }

    public function postUpdatedColaborador(Request $request){
        $colaborador = colaborador::where('legajo','=',$request->input('legajo'))->first();

        if($colaborador===null){
            return response()->json([
                'message'=>'No se encontro ningun dato expecifico del colaborador',
            ]);
        }


        $colaborador->legajo=$request->input('legajo');
        $colaborador->cedula=$request->input('cedula');
        $colaborador->password=$request->input('cedula');
        $colaborador->nombre=$request->input('nombre');
        $colaborador->apellido=$request->input('apellido');
        $colaborador->hora=$request->input('hora');
        $colaborador->save();

        return response()->json([
            'message'=>'Se modifico el colaborador con exito!',
        ]);

    }

    public function getDatosMesColaboradores($mes){

        $allDatos=DB::select("SELECT CONCAT(c.nombre,' ',c.apellido) AS nombre, c.cedula, COUNT(*) AS cantidad
        FROM colab_menu cm
        INNER JOIN colaborador c ON cm.id_colab=c.id_colaborador
        WHERE cm.id_colab IN(SELECT csub.id_colaborador FROM colaborador csub) AND MONTH(cm.fecha)='$mes'
        GROUP BY c.nombre, c.apellido,c.cedula");


        if(count($allDatos)==0){
            return response()->json([
                'message'=>'No se encontro ningun dato expecifico de este mes',
            ]);
        }

        return response()->json([
            'message'=>'Exito!',
            'allDatos'=>$allDatos,
        ]);

    }

    public function getPuntuacionMensual($mes){
        $allPuntuacion=DB::select("SELECT vm.*, c.cedula, CONCAT(c.nombre,' ',c.apellido) AS nombre 
                                    FROM valoracion_mensual vm
                                        INNER JOIN colaborador c ON vm.id_colab=c.id_colaborador
                                            WHERE MONTH(vm.fecha)-1='$mes'
                                                ORDER BY c.cedula desc");

        if(count($allPuntuacion)==0){
            return response()->json([
                'message'=>'No se encontro ningun dato expecifico de esta mes.'
            ]);
        }

        $pregunta1=0;
        $pregunta2=0;
        $pregunta3=0;
        $pregunta4=0;
        $cantidad=0;
        foreach ($allPuntuacion as $value) {
            $pregunta1=$value->pregunta1;
            $pregunta2=$value->pregunta2;
            $pregunta3=$value->pregunta3;
            $pregunta4=$value->pregunta4;
            $cantidad=0;
            $cantidad++;
        }
        $totalGeneral1=$pregunta1/$cantidad;
        $totalGeneral2=$pregunta2/$cantidad;
        $totalGeneral3=$pregunta3/$cantidad;
        $totalGeneral4=$pregunta4/$cantidad;

        return response()->json([
            'message'=>'Exito!',
            'pregunta1'=>$totalGeneral1,
            'pregunta2'=>$totalGeneral2,
            'pregunta3'=>$totalGeneral3,
            'pregunta4'=>$totalGeneral4,
            'allPuntuacion'=>$allPuntuacion
        ]);
    }
}
