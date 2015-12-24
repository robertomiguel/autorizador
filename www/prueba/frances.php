<?php 
$interes = 0;

// Interes Mensual
$unidad_de_tiempo = 30;

$capital = 10000;

$tiempo = 6 * $unidad_de_tiempo;

$tasa = 2.6;

$interes = $capital * $tasa * $tiempo;
$interes = $interes / ( 100 * $unidad_de_tiempo );

echo 'Capital: ' . $capital . '<br>';
echo 'Interes: ' . $interes;
?>