<!DOCTYPE html>
<style>

  .neologo{
    max-height: 30px;
  }
</style>
<html>
  <head>
    <title>@yield('title', 'HOME BANKING')</title>
    {{--<meta name="viewport" content="width=device-width, initial-scale=1.0">--}}
    <meta name="viewport" content="width=device-width,initial-scale=1,maximun-scale=1">
    <link rel="shortcut icon" href="favicon.png" />
<?php //seleccionar tema
$ini = parse_ini_file("../app/config/neoweb.ini");
$sel = $ini['seleccion'];
$tema = $ini[$sel];
 ?>
<?php /*
    {{ HTML::style('/assets/css/bootstrap.min.css', array('media' => 'screen')) }}
*/ ?>        
    {{ HTML::style("/css/temas/$tema/jquery-ui.min.css", array('media' => 'screen')) }}
    {{ HTML::style("/css/temas/$tema/theme.css", array('media' => 'screen')) }}
     <link rel="stylesheet" href="tema/css/estilos.css">

    {{ Cargar::stylesheet(array(
                          '/css/bootstrap.css',
                          '/css/bootstrap-theme.css',
                          '/css/global.css',
                          '/css/dataTables.jqueryui.css',
                          '/css/jquery.dataTables.min.css',
                          )) }}    

    {{ Cargar::javascript(array(
                                '/js/jquery-1.11.2.min.js',
                                '/js/bootstrap.min.js',
                                '/js/jquery-ui.min.js',
                                '/js/jquery.dataTables.min.js',
                                '/js/posnet.js',
                                )) }}


  </head>
  <body>
  
      
        @yield('content')
  
       {{-- <div class='footer navbar-fixed-bottom ui-state-highlight' align="center">
                      <a href="http://www.neosistemassrl.com/" target="_blank">
                        <img src="img/neo.jpg" class="img-responsive neologo img-rounded">
                      </a>
                     </div> --}}
       
  </body>
</html>