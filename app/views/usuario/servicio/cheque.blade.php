<table class="table letra11 table-hover table-striped">
 <thead>
 	<tr>
 		<th>Fecha</th><th>Banco</th><th>Número</th>
 		<th>Acreditación</th><th>Monto</th>
 	</tr>
 </thead>
	@foreach ($cheques as $cheque)
	<tr>
		<td>{{Formatos::fecha($cheque->fecha_cheque)}}</td>
		<td>{{$cheque->nombre_banco}}</td>
		<td>{{$cheque->numero_cheque}}</td>
		<td>{{Formatos::fecha($cheque->fecha_acreditacion)}}</td>
		<td>{{Formatos::moneda($cheque->monto_cheque)}}</td>
	</tr>
	@endforeach
</table>				
