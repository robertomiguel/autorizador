@extends ('cabecera')

@section ('content')

@if (Auth::check())
 Logueado <br>
@endif

Listado Ajax: <br>

<table id="datawindow" class="display">
<thead>
<tr>
	<th id="th1"></th>
	<th id="th2"></th>
</tr>
</thead>
</table>
<hr>
<button class="ui-state-default ui-corner-all" onclick="cargarSocios()">Mostrar Socios</button>
<button class="ui-state-default ui-corner-all" onclick="cargarComercios()">Mostrar Comercios</button>

<script>
	var dw_tabla = $('#datawindow').dataTable();
	$(document).ready(function() {
		//cargarSocios();
} );
function cargarSocios(){
	dw_tabla.fnDestroy();
	$("#th1").text("Apellido");
	$("#th2").text("Nombre");
	    $('#datawindow').dataTable( {
	    	"scrollY" :  400,
			"scrollCollapse": true,
			"jQueryUI":       true,
            "bDestroy": true,
            "pagingType": "full_numbers",
        	"ajax": {
            	"url": "cargarSocios",
            	"dataSrc": ""
        	},
        	"columns": [
            	{ "data": "apellido" },
            	{ "data": "nombre" }
        	]
    	});
}

function cargarComercios(){
	dw_tabla.fnDestroy();
	$("#th1").text("Razon Social");
	$("#th2").text("Nombre Fantasía");
	    $('#datawindow').dataTable( {
	    	"scrollY" :  400,
			"scrollCollapse": true,
			"jQueryUI":       true,
            "bDestroy": true,
            "pagingType": "full_numbers",
        	"ajax": {
            	"url": "cargarComercios",
            	"dataSrc": ""
        	},
        	"columns": [
            	{ "data": "com_razon_social" },
            	{ "data": "com_nombre_fantasia" }
            ]
    	});
}

</script>


@stop