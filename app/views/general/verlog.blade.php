<h2>Archivo log: {{"errores_$archivo.log"}}</h2>
<hr>

<pre>
<?php
try {
include "../temp/logs/errores_$archivo.log";
} catch (Exception $e) {
echo 'Error al leer el archivo';
}
?>
</pre>