<table class="table letra12 table-hover table-striped">
	<thead>
		<tr>
			<td align="left"><b>Nro. Cuenta</b></td>
			<td align="right"><b>Saldo</b></td>
			<td></td>
		</tr>
	</thead>
	<tbody>
		@foreach($cuentas as $cuenta)
			<tr>
				<td>
					<span class="glyphicon glyphicon-list-alt"></span> {{$cuenta->numero_cuenta}}
				</td>
				<td align="right">
					{{Formatos::saldocuenta($cuenta->numero_cuenta)}}
				</td>
				<td align="right">
					{{--<button class="ui-state-default ui-corner-all" onclick="verDetalle({{$cuenta->numero_cuenta}})">
															<span class="glyphicon glyphicon-search"></span> Ver detalle</button>--}}
					@if (!Formatos::esCelular())
						<button class="ui-state-default ui-corner-all" onclick="verDetallePro({{$cuenta->numero_cuenta}})">
						<span class="glyphicon glyphicon-search"></span> Ver detalle</button>
					@endif
				</td>
			</tr>
		@endforeach
	</tbody>
</table>