<table class="table letra12 table-hover table-striped">
 <thead>
 	<tr>
 		<td align='center'><b>Fecha Emisión</b></td>
 		<td align='center'><b>Nro. Préstamo</b></td>
 		<td align='center'><b>Nombre del Plan</b></td>
 		<td align='center'><b>Monto Final</b></td>
 		<td align='center'><b>Saldo</b></td>
 	</tr>
 </thead>
	@foreach ($prestamos as $prestamo)
	<tr>
		<td align='center'>{{Formatos::fecha($prestamo->fecha_emision_prestamo)}}</td>
		<td align='center'>{{$prestamo->numero_prestamo}}</td>
		<td>{{$prestamo->nombre_plan}}</td>
		<td align='right'>{{Formatos::moneda($prestamo->monto_total_final)}}</td>
		@if ($prestamo->saldo<=0)
			<td align='right'>Cancelado</td>
		@else
			<td align='right'>{{Formatos::moneda($prestamo->saldo)}}</td>
		@endif
	</tr>
	@endforeach
</table>
