<table class="table letra11 table-hover table-striped">
	<caption><div class="text-center"><strong>Detalle de Consumos</strong></div></caption>
	<thead align="center">
		<tr>
			<th class="text-center">Fecha</th>
			<th class="text-center">Comercio</th>
			<th class="text-center">Cuotas</th>
			<th class="text-center">Monto</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($liqconsumos as $consumo)
			<tr>
				<td align='center'>{{Formatos::fecha($consumo->fecha_consumo)}}</td>
				<td align='left'>{{Formatos::capital($consumo->comercio)}}</td>
				<td align='center'>{{$consumo->cuotas}}</td>
				<td align='right'>{{Formatos::moneda($consumo->importe_compra)}}</td>
			</tr>	
		@endforeach
	</tbody>
</table>
