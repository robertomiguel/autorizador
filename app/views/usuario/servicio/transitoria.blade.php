<table class="table letra10 table-hover table-striped">
 <thead>
 	<tr>
 		<th>Nombre Servicio</th><th>Alta</th><th>Vencimiento</th><th>Código</th>
 		<th>Monto Límite</th><th>Monto Neto</th><th>Descripción</th>
 	</tr>
 </thead>
	@foreach ($transitorias as $trans)
	<tr>
		<td align='center' style="max-width: 100px;">{{$trans->nombre_servicio}}</td>
		<td align='center'>{{Formatos::fecha($trans->fecha_alta)}}</td>
		<td align='center'>{{Formatos::fecha($trans->fecha_vencimiento)}}</td>
		<td align='center'>{{$trans->nro_aye_trans}}</td>
		<td align='center'>{{Formatos::moneda($trans->monto_limite)}}</td>
		<td align='center'>{{Formatos::moneda($trans->monto_neto)}}</td>
		<td align='center'>{{$trans->descripcion_corta}}</td>
	</tr>
	@endforeach
</table>
