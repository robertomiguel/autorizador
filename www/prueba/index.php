<?php 

$fuente = 'neuropol_nova_xp_bold.ttf';    
//aprox 39 caracteres máximo
$texto = strtoupper(utf8_decode("Asoc. Italiana de Socorros Mutuos"));

						  //Horizontal, Vertical
$im = @imagecreatetruecolor(       600, 60) or die("Error al crear imagen.");

// fondo transparente
imagealphablending($im, false);
$transparency = imagecolorallocatealpha($im, 0, 0, 0, 127);
imagefill($im, 0, 0, $transparency);
imagesavealpha($im, true);

// Añadimos la fuente, en este caso, incluida en el site.
//$fuente = 'codigo-barra_inthrp48dmtt.ttf';

// Asignamos un color para el texto: blanco en RGB.
$color = imagecolorallocate($im, 0, 0, 250);
// Definimos el Texto

list($x, $y) = ImageTTFCenter($im, $texto, $fuente, 20);
// Añadimos el texto a la imagen
//           imagen, tamaño, ángulo,  x,  y, color,  fuente, texto
imagettftext(   $im,     17,      0, $x, $y, $color, $fuente, $texto);


//header("Content-type: image/png"); 

imagepng($im, 'imagen.png',9); 
imagedestroy($im);

function ImageTTFCenter($image, $text, $font, $size, $angle = 45) 
{
    $xi = imagesx($image);
    $yi = imagesy($image);

    $box = imagettfbbox($size, $angle, $font, $text);

    $xr = abs(max($box[2], $box[4]));
    $yr = abs(max($box[5], $box[7]));

    $x = intval(($xi - $xr) / 2);
    $y = intval(($yi + $yr) / 2);

    return array($x, $y);
}

 ?>

 <style>
	html {background: gray;}
</style>
<html>
	<body>
        <div align="center">
            <hr>
                <img src="/prueba/imagen.png" alt="">
            <hr>
        </div>
	</body>
</html>