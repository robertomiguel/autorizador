<?php
class Formatos {
static public function mostrarFecha(){
  $dt = new DateTime('NOW');
  return $dt->format('d/m/Y H:i');
}
static public function fechaHoraActual(){
    $dt = new DateTime('NOW');
    $formatoFecha = DB::select("SELECT ISDATE('2016/12/31') as valido");
    if ( $formatoFecha[0]->valido == 0) {
      return $dt->format('d-m-Y H:i:s'); //H:i:s
    } else {
      return $dt->format('Y-m-d H:i:s'); //H:i:s
    }
}
static public function tarjeta($nro_tarjeta){
  return preg_replace('/(?!^.?)[0-9](?!(.){0,3}$)/', '*', $nro_tarjeta);
}
static public function tarjetaguion($nro){
  $nro = substr_replace($nro,'-',4,0);
  $nro = substr_replace($nro,'-',9,0);
  $nro = substr_replace($nro,'-',14,0);
  return $nro;
}
static public function fechaActual(){
  /*$dt = new DateTime('NOW');
  return $dt->format('Y-m-d'); */
    $dt = new DateTime('NOW');
    $formatoFecha = DB::select("SELECT ISDATE('2016/12/31') as valido");
    if ( $formatoFecha[0]->valido == 0) {
      return $dt->format('d-m-Y'); //H:i:s
    } else {
      return $dt->format('Y-m-d'); //H:i:s
    }

}
static public function dni($dni){
return number_format($dni,0,',','.');
}

static public function moneda($moneda){
  if($moneda==''){return '';}
  return number_format($moneda * 1,2,',','.');
}

static public function fecha($fecha){
  if ($fecha==null) {return null;}
//  2009-02-01 a 01-02-2009
  $dia = substr($fecha,8,2);
  $mes = substr($fecha,5,2);
  $anio = substr($fecha,0,4);
  $fecha = $dia."-".$mes."-".$anio;
  return $fecha;
}

static public function periodo($fecha){
  if ($fecha==null) {return null;}

//  2009-02-01 a 01-02-2009
$meses = array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio',
               'Agosto','Setiembre','Octubre','Noviembre','Diciembre');
  $mes = substr($fecha,5,2)*1;
  $anio = substr($fecha,0,4);
  $fecha = $meses[$mes].' '.$anio;
  return $fecha;
}

static public function siono($activo){
if ($activo==1) {return "SI";} else {return "NO";}
}

static public function capital($texto){
return ucwords(strtolower($texto));
}

static public function saldocuenta($cuenta){
    
    $cuentas = cuentasAhorro::socioCuentas();
    
    $sql="  Select
        sum ( case 
        when (tipo_cbte % 2)= 0 then monto
        else monto * (-1)
        end) as saldo
        from movimientos_ahorro
        where movimientos_ahorro.nro_empresa = 1
         and movimientos_ahorro.nro_sucursal  = 1
         and numero_cuenta = $cuenta";

    $saldos = DB::select($sql);
    foreach ($saldos as $saldo) {
    $saldo = $saldo->saldo;
    }
    return number_format($saldo * 1,2,',','.');
  }

static public function titulo($nombre){

  if (file_exists(public_path().'/img/titulo.png')) {return;}
  // Añadimos la fuente, en este caso, incluida en el site.
  //$fuente = 'codigo-barra_inthrp48dmtt.ttf';
  //$fuente = '../letras/neuropol_nova_xp_bold.ttf';    
  $fuente = '../letras/urw.ttf';
  // Tamaño
  $size = 17;
  //Texto: aprox 39 caracteres máximo
  $texto = strtoupper(utf8_decode($nombre));

                //Horizontal, Vertical
  $im = @imagecreatetruecolor(       600, 60) or die("Error al crear imagen.");

  // fondo transparente
  imagealphablending($im, false);
  $transparency = imagecolorallocatealpha($im, 0, 0, 0, 127);
  imagefill($im, 0, 0, $transparency);
  imagesavealpha($im, true);


  // Asignamos un color para el texto: blanco en RGB.
  //$color = imagecolorallocate($im, 0, 0, 250); // azul
  $color = imagecolorallocate($im, 184, 184, 188);
  $gris = imagecolorallocate($im, 184, 184, 188);

  // centrar texto
  $xi = imagesx($im);
  $yi = imagesy($im);

  $box = imagettfbbox($size, 45, $fuente, $texto);

  $xr = abs(max($box[2], $box[4]));
  $yr = abs(max($box[5], $box[7]));

  $x = intval(($xi - $xr) / 2);
  $y = intval(($yi + $yr) / 2);


  // Añadimos el texto a la imagen
  //           imagen, tamaño, ángulo,  x,  y, color,  fuente, texto
  imagettftext(   $im,     $size,      0, $x - 22, $y, $color, $fuente, $texto);

  imagepng($im, public_path().'/img/titulo.png',9); 
  imagedestroy($im);

}

  static public function esCelular(){
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
  }

}