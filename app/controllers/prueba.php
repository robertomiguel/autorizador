<?php 
class Prueba extends BaseController {

//--- Prefijos : get , post, any

    public function getIndex()
    {
        $personas = DB::select("
                                SELECT TOP 1000 soc_numero_socio as nro_socio, (apellido + ' ' + nombre) as nombre
                                  FROM personas 
                                 WHERE es_socio = 1
                                ");
        $datos = array();
        $datos['personas'] = $personas;
        $pdf = \PDF::loadView('prueba.listado', $datos)->setPaper('a4');
        return $pdf->stream("personas.pdf");   

    //pasar una vista
    //$html = View::make('prueba.listado')->with('personas',$personas);
        
    //$pdf = App::make('dompdf')->setPaper('a4'); // ->setOrientation('landscape'); //apaisado
    //$pdf->loadHTML($html);
    //return $pdf->stream(); //mostrar pdf
     //return $pdf->download('socios.pdf');  //descargar pdf

    }

    public function postSocios(){
        return Persona::listaSocios();
    }

    public function postComercios(){
        return Persona::listaComercios();
    }

}