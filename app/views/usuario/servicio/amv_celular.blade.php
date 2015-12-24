<table class="table letra13 table-hover table-striped">
	@foreach($cuentas as $cuenta)
		<tr>
			<td>
				<span class="glyphicon glyphicon-list-alt"></span> {{$cuenta->numero_cuenta}}
			</td>
			<td align="right">{{Formatos::saldocuenta($cuenta->numero_cuenta)}} </td>
			
			<td align="right">
				<button class="ui-state-default ui-corner-all" onclick="detallecuenta({{$cuenta->numero_cuenta}})">
				<span class="glyphicon glyphicon-search"></span> Ver detalle</button>
			</td>
		</tr>
	@endforeach
</table>