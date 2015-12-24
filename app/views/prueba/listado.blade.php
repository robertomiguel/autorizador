<!DOCTYPE html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>	
<body>
	<div align="center">
		<img src="img/neo.jpg">
	</div>
<strong>
	Listado de Socios: <br>
</strong>

<table>

	<thead>
		<tr>
			<th>Nro</th>
			<th>Nombre  </th>
		</tr>
	</thead>

	<tbody>
		@foreach ($personas as $persona)
		<tr>
			
			<td>{{$persona->nro_socio}}</td><td>{{$persona->nombre}}</td>
			
		</tr>	
		@endforeach
	</tbody>

</table>
<script type="text/php">
    if ( isset($pdf) ) {
        $font = Font_Metrics::get_font("helvetica", "bold");
        $pdf->page_text(20, 20, "Hoja {PAGE_NUM} de {PAGE_COUNT}",
                        $font, 8, array(0,0,0));
    }
</script>    
<hr>
Total: {{count($personas)}}
</html>
