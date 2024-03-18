<?php

namespace App\Http\Controllers;

use App\Models\colaborador;
use Illuminate\Support\Facades\Auth;
use App\Models\menu;
use App\Models\menu_dia;
use App\Models\postre;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }


    public function showMenu(){
        return view('menu.showMenu');
    }

    public function showPedido(){
        return view('menu.showPedido');
    }

    public function showPedidosDia(){
        return view('menu.showPedidosDia');
    }

    public function showUserMenu(){
        return view('menu.showUserMenu');
    }

    public function getPostre(){
        $postres['postre']=postre::where('activo','=',1)->get();
        return $postres; 
    }

    public function getMenu($fecha){
        $menu_dia=DB::select("SELECT md.id_menu_dia,
                            m1.id_menu as id_menu1, m1.menu_description as menu_description1,
                            m2.id_menu as id_menu2 , m2.menu_description as menu_description2, 
                            m3.id_menu as id_menu3, m3.menu_description as menu_description3,
                            md.id_postre1,md.id_postre2,md.id_postre3
                            FROM menu_dia md
                            INNER JOIN menu m1 ON md.id_menu1=m1.id_menu
                            INNER JOIN menu m2 ON md.id_menu2=m2.id_menu
                            INNER JOIN menu m3 ON md.id_menu3=m3.id_menu
                            WHERE fecha='$fecha'
                            LIMIT 1");
        if(count($menu_dia)==0){
            $menu=null;
        }else{
            $menu=$menu_dia[0];
        }
        return response()->json($menu);
    }

    public function createMenu(Request $request){

        $miercoles = new DateTime();
        $miercoles->modify('+1 day');

        while ($miercoles->format('N') != 3) { 
            $miercoles->modify('+1 day');
        }

        $vencimiento=date_format($miercoles, 'Y-m-d');
       
        $newDate = date("Y-m-d", strtotime($request->input('dia')));
        $id_colaborador=Auth::user()->id;
        $nuevoMenu1= new menu();
        $nuevoMenu1->menu_description=$request->input('menu1');
        $nuevoMenu1->save();
        $nuevoMenu2= new menu();
        $nuevoMenu2->menu_description=$request->input('menu2');
        $nuevoMenu2->save();
        $nuevoMenu3= new menu();
        $nuevoMenu3->menu_description=$request->input('menu3');
        $nuevoMenu3->save();

        $menu_dia= new menu_dia();
        $menu_dia->id_menu1=$nuevoMenu1->id_menu;
        $menu_dia->id_menu2=$nuevoMenu2->id_menu;
        $menu_dia->id_menu3=$nuevoMenu3->id_menu;
        $menu_dia->id_postre1=$request->input('postre1');
        $menu_dia->id_postre2=$request->input('postre2');
        $menu_dia->id_postre3=$request->input('postre3');
        $menu_dia->id_user=$id_colaborador;
        $menu_dia->fecha=$newDate;
        $menu_dia->vencimiento=$vencimiento;
        $menu_dia->save();

        return response()->json([
            'message'=>'Se creo el Menú con exito!'
        ]);
    }

    public function createPostre(Request $request){
        $nuevoPostre= new postre();
        $nuevoPostre->postre_description=$request->input('nuevoPostre');
        $nuevoPostre->activo=1;
        $nuevoPostre->save();

        return response()->json([
            'message'=>'Se creo el Postre con exito!'
        ]);

    }

    public function desactivarPostre(Request $request){
        $postre=postre::find($request->input('id'));
        $postre->activo=0;
        $postre->save();

        return response()->json([
            'message'=>'Se elimino el postre!'
        ]);
    }

    public function updateMenu(Request $request){
        $id_colaborador=Auth::user()->id;

        $menu1=menu::find($request->input('id_menu1'));
        $menu1->menu_description=$request->input('menu1');
        $menu1->save();

        $menu2=menu::find($request->input('id_menu2'));
        $menu2->menu_description=$request->input('menu2');
        $menu2->save();

        $menu3=menu::find($request->input('id_menu3'));
        $menu3->menu_description=$request->input('menu3');
        $menu3->save();

        $menu_dia=menu_dia::find($request->input('id'));
        $menu_dia->id_postre1=$request->input('postre1');
        $menu_dia->id_postre2=$request->input('postre2');
        $menu_dia->id_postre3=$request->input('postre3');
        $menu_dia->id_user=$id_colaborador;
        $menu_dia->save();

        return response()->json([
            'message'=>'Se actualizo el Menú con exito!'
        ]);
    }

    public function getPedidos($fecha){

        $menus=DB::select("SELECT cm.id_menu_dia,
        CASE DAYOFWEEK(cm.fecha)
        WHEN 2 THEN 'Lunes'
        WHEN 3 THEN 'Martes'
        WHEN 4 THEN 'Miércoles'
        WHEN 5 THEN 'Jueves'
        WHEN 6 THEN 'Viernes'
        END AS dia_semana,cm.fecha,cm.id_menu, m.menu_description,COUNT(*) AS cantidad
        FROM colab_menu cm
        INNER JOIN menu m ON cm.id_menu=m.id_menu
        WHERE WEEK(cm.fecha)=WEEK('$fecha')
        GROUP BY cm.id_menu_dia,cm.id_menu,cm.fecha,m.menu_description");

        $postres=DB::select("SELECT cm.id_menu_dia,
        CASE DAYOFWEEK(cm.fecha)
        WHEN 2 THEN 'Lunes'
        WHEN 3 THEN 'Martes'
        WHEN 4 THEN 'Miércoles'
        WHEN 5 THEN 'Jueves'
        WHEN 6 THEN 'Viernes'
        END AS dia_semana,cm.fecha,cm.id_postre, p.postre_description, COUNT(*) AS cantidad
        FROM colab_menu cm
        INNER JOIN postre p ON cm.id_postre=p.id_postre
        WHERE WEEK(cm.fecha)=WEEK('$fecha')
        GROUP BY cm.id_menu_dia,cm.id_postre,cm.fecha,p.postre_description;");

        if(count($menus)==0 || count($postres)==0){
            return response()->json([
                'message'=>'No se encontro ningun dato expecifico de esta semana'
            ]);
        }

        // Nuevo array para organizar por día de la semana
        $organizadoPorDia = [];

        // Combinar los dos arrays
        foreach ($menus as $menu) {
            $diaSemana = $menu->dia_semana;
            $organizadoPorDia[$diaSemana]["menu"][] = $menu;
        }

        foreach ($postres as $postre) {
            $diaSemana = $postre->dia_semana;
            $organizadoPorDia[$diaSemana]["postre"][] = $postre;
        }

        return response()->json([
            'message'=>'Exito!',
            'allDatos'=> $organizadoPorDia
        ]);

    }

    public function getPedidosDia($fecha,$hora){
        

        $menus=DB::select("SELECT m.menu_description AS menu, cm.pan, p.postre_description AS postre, cm.hora, COUNT(*) AS cantidad 
        FROM colab_menu cm
        INNER JOIN menu m ON cm.id_menu=m.id_menu
        INNER JOIN postre p ON cm.id_postre=p.id_postre
        WHERE cm.fecha='$fecha' and cm.hora='$hora'
        GROUP BY m.menu_description,p.postre_description,cm.hora, cm.pan
        ORDER BY cm.hora asc");

        if(count($menus)==0){
            return response()->json([
                'message'=>'No se encontro ningun dato expecifico de esta semana'
            ]);
        }

        return response()->json([
            'message'=>'Exito!',
            'allDatos'=> $menus
        ]);
    }

    public function getPedidoInvitado($legajo,$fecha){

        $colab=colaborador::where('legajo','=',$legajo)->first();

        $menus=DB::select("SELECT cm.id_menu_dia, cm.hora,cm.pan,
        CASE DAYOFWEEK(cm.fecha)
        WHEN 2 THEN 'Lunes'
        WHEN 3 THEN 'Martes'
        WHEN 4 THEN 'Miércoles'
        WHEN 5 THEN 'Jueves'
        WHEN 6 THEN 'Viernes'
        END AS dia_semana,cm.fecha,cm.id_menu, m.menu_description,cm.id_postre, p.postre_description
        FROM colab_menu cm
        INNER JOIN menu m ON cm.id_menu=m.id_menu
        INNER JOIN postre p ON cm.id_postre=p.id_postre
        WHERE WEEK(cm.fecha)=WEEK('$fecha') AND cm.id_colab='$colab->id_colaborador'
        GROUP BY cm.id_menu_dia,cm.id_menu,cm.fecha,m.menu_description,cm.id_postre,p.postre_description,cm.hora,cm.pan");

        if(count($menus)==0){
            return response()->json([
                'message'=>'No se encontro ningun dato expecifico de esta semana.'
            ]);
        }

        return response()->json([
            'message'=>'Exito!',
            'allDatos'=> $menus,
            'nombre'=>$colab->nombre." ".$colab->apellido
        ]);
    }
}
