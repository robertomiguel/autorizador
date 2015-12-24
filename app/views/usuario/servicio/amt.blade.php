<table class="table letra12 table-hover table-striped">
 <thead>
	 	<tr>
	 		<th>Nro. Certificado</th><th>Depósito</th><th>Vencimiento</th>
	 		<th>Capital</th><th>Interes</th>
	 	</tr>
	 </thead>
	 <tbody>
	@foreach ($terminos as $term)
		<tr>
			<td>{{$term->numero_certificado}}</td>
			<td>{{Formatos::fecha($term->fecha_deposito)}}</td>
			<td>{{Formatos::fecha($term->fecha_vencimiento)}}</td>
			<td>{{Formatos::moneda($term->capital_certificado)}}</td>
			<td>{{Formatos::moneda($term->interes_certificado)}}</td>
		</tr>
	@endforeach
</tbody>
</table>