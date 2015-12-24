@extends ('cabecera')

@section ('content')

Listado con Json y Ajax <br>

<button class="ui-state-default ui-corner-all" onclick="cargarLista('listado/socios')">Mostrar Socios</button>
<button class="ui-state-default ui-corner-all" onclick="cargarLista('listado/comercios')">Mostrar Comercios</button>

<table id="datawindow"  class="display" cellspacing="0" width="100%">
	<thead>
	<tr>		
		<th>1</th><th>2</th><th>3</th>
	</tr>
	</thead>
</table>

<script>
	
$(document).ready(function(){
	$('#datawindow').dataTable({"jQueryUI": true});
});

function cargarLista($url) {

$('#datawindow').dataTable( {
	    	"scrollY" :  400,
			"scrollCollapse": true,
			"jQueryUI":       true,
            "bDestroy": true,
            "pagingType": "full_numbers",
        	"ajax": {
            	"url": $url,
                "type": "POST",
            	"dataSrc": ""
        	},
        	"columns": [
            	{ "data": "a1" },
            	{ "data": "a2" },
                { "data": "a3" }
        	]
    	});

}




</script>
@stop