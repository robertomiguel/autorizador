<?php
//--- SI URL NO EXISTE IR AL RAIZ
App::missing(function($exception)
  {return Redirect::to('/');
});

//--- PRUEBA ANDROID
Route::get('neotest', function() {   
    //$xml = simplexml_load_file('c:/neoweb/xml/personas.xml');
    //return View::make('verxml')->with('xml',$xml);
    //echo $xml->personas_row[0]->apellido . $xml->personas_row[0]->nombre ;
    $plan = '11 ';
    $monto = '250000.12 ';
    $archivo = 'soc0001001';

    $salida = shell_exec('d:\autorizador_exe\gen_aye.exe '.$plan.$monto.$archivo);
    $xml = simplexml_load_file('c:/neoweb/temp/'.$archivo.'.xml');
    echo "Plan:$plan - Monto:$monto - Archivo:$archivo <br>";
    foreach ($xml as $dato) {
      echo '   Plan:'.$dato->plan . '<br>';
      echo '  Monto:'.Formatos::moneda($dato->monto) . '<br>';
      echo 'Archivo:'.$dato->archivo . '<br>';
    }
    return;
    
});

Route::post('licencia', function() {

  $nombre_empresa = Input::get('empresa','no hay');
  $clave_acceso   = Input::get('clave','no hay');

  if ($clave_acceso == 'no hay') { return; }
  if ($clave_acceso <> '123456.a') { return 'error 1333';}

  if ($nombre_empresa =='no hay') { return; }
  $licencia = Licencia::getLicencia($nombre_empresa);
  if ( count($licencia) <= 0) { return 'error 3222'; }
  
  $licencia_clave = $licencia[0]->clave;
  $licencia_fecha = $licencia[0]->fecha_clave;

  return utf8_decode($licencia_fecha).'|'.utf8_decode($licencia_clave);

});

/*
Route::get('neotest', function()
{   
    $sql = " SELECT TOP 20 apellido, nombre 
               FROM personas
           ";

    $datos = DB::select($sql);

    //return json_encode($datos, JSON_FORCE_OBJECT);
    return json_encode($datos);
    //return xmlrpc_encode($datos);
});
*/

App::error(function(Exception $exception, $code)
{
      if(Request::ajax() && $code<>500) {
        return Response::make('Su sesión expiró....');
      }
      if ($code==404){
        return Redirect::to('/')->withFlashMessage('Inicie sesión.');
      }
      if ($code==405){
        return 'Su sesión expiró.';
      }
      
    if ( ! Config::get('app.debug')) {
        // Retorna una vista o lo que creas conveniente
      //Log::useFiles(storage_path().'/logs/errores_produccion.log');
      $ip = Request::getClientIp();
      try {
      $nro_persona = Auth::user()->nro_persona;
      } catch (Exception $e) {
      $nro_persona = 'no disponible';
      }
    Log::error("[código:$code][ip:$ip][persona:$nro_persona]\n".$exception->getMessage());

        return 'Servicio no disponible. Intente más tarde.';
    }
});

//--- Ruta por defecto
Route::resource('/', 'inicioControlador');

//--------------- RUTAS QUE ACCEDE ADMIN
Route::group(array('before' => 'auth|soyadmin'), function(){
    Route::resource('admin', 'adminControlador@inicio');
}); //--------- FIN ADMIN ACCESS

//--------------- RUTAS QUE ACCEDE SOCIO O SOCIO/COMERCIO
Route::group(array('before' => 'auth|soyusuario'), function(){
    //Route::controller('usuario','usuarioControlador');
    Route::controller('socio','socioControlador');
}); //--------- FIN ADMIN ACCESS

//--------------- RUTAS QUE ACCEDE COMERCIO
Route::group(array('before' => 'auth|soycomercio'), function(){
    Route::controller('autorizaciones','comercioControlador');
    Route::post('validar','comercioControlador@validar');
}); //--------- FIN ADMIN ACCESS

//----- RUTAS QUE ACCEDE EL USUARIO POR PRIMERA VEZ PARA CAMBIAR CLAVE O RESETEAR CLAVE
Route::group(array('before' => 'auth'), function(){
    Route::post('cambiarclave','loginControlador@cambiarpass');
    Route::get('cambiarclave', function()
    {
      return View::make('login.cambiarclave')->withFlashMessage('');
    });
}); //--------- FIN ADMIN ACCESS

//listado de prueba
Route::controller('listado', 'prueba');

//--- Login
Route::post('entrar', 'loginControlador@acceso');
Route::get('salir', 'loginControlador@salir');


//--- cargar usuario de prueba
Route::get('verlog/{archivo}', function($archivo)
{ /*
  $clave = '123';
  $email = 'super@apla.com';
  $nro_persona = 1064;
  $estado = 0; //0=cambiar pass, 1=acceso
  $nivel = 2; //1=usuario, 2=comercio, 3=usuario y comercio, 99=admin

  $insert = DB::insert("insert into hb_usuario
  						 ( nro_persona,    clave,    email,   estado,  nivel) values 
  						 ($nro_persona,	'$clave', '$email',	 $estado, $nivel)
  					  ");

	return 'Insertado: '.$insert; */

return View::make('general.verlog')->with('archivo',$archivo);
 
});

