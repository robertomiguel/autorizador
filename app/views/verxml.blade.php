@extends ('cabecera')

@section ('content')

<table class="table sombra letra11">
	<thead>
		<td>Apellido</td><td>Nombre</td><td>Fecha Alta</td>
	</thead>
	<tbody>
		@foreach ($xml as $persona)
			<tr>
				<td>{{$persona->apellido}}</td>
				<td>{{$persona->nombre}}</td>
				<td>{{Formatos::fecha($persona->soc_fecha_alta)}}</td>
			</tr>
		@endforeach

	</tbody>
</table>
@stop