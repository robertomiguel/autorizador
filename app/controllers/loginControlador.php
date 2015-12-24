<?php 
class LoginControlador extends BaseController {

    public function acceso()
    {
    $datos = array(
            'email'=> Input::get('email'),
            'clave'=> Input::get('clave')
        );

	if (Auth::attempt($datos, false))
      {

      	return Redirect::to('/autorizaciones');
      } 
       
      return Redirect::to('/')->withFlashMessage('E-Mail y/o Contraseña incorrecta.');

    }

    public function salir(){
    	Auth::logout();
    	return Redirect::to('/');
    }

    public function cambiarpass() {
      $clave = array(
            'actual'  => Input::get('claveactual'),
            'nueva'   => Input::get('nuevaclave'),
            'confirma'=> Input::get('confirmarclave')
        );

      if ( trim($clave['nueva'])    == '' or
           trim($clave['actual'])   == '' or
           trim($clave['confirma']) == '' ) {
        return View::make('login.cambiarclave')->with('erroractual','No puede haber claves en blanco');
      }
      
      if ( trim($clave['nueva']) == trim($clave['actual']) ) {
        return View::make('login.cambiarclave')->with('errornueva','La nueva clave no puede ser igual a la Actual');
      }

      if ($clave['actual'] <> Auth::user()->clave ) {
        return View::make('login.cambiarclave')->with('erroractual','La clave actual es incorrecta');
      }
      
      if ( strlen(trim($clave['nueva'])) < 6 ) {
        return View::make('login.cambiarclave')->with('errornueva','La nueva clave no puede tener menos de 6 caracteres');
      }

      if ( trim($clave['nueva']) <> trim($clave['confirma']) ) {
        return View::make('login.cambiarclave')->with('errorconfirma','La nueva clave no coincide con la confirmación');
      }
      
      $clave = trim($clave['nueva']);
      $nro_persona = Auth::user()->nro_persona;

      $retorno = DB::update("
                              UPDATE hb_usuario
                                     SET clave = '$clave', estado = 1
                              WHERE nro_persona = $nro_persona
                            ");
      Auth::logout();
      return Redirect::to('/')->withFlashMessage('Clave Cambiada. Use la nueva Clave');
    }

}