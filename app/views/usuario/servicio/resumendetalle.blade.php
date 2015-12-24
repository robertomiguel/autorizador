<style>
	.modal-dialog, .modal-content {
    height: 90%;
    width: 800px
}

.modal-body {
    max-height: calc(100% - 120px);
    overflow-y: scroll;
}
.fuenteroman {
        font-family: "Times New Roman";
}
.fuenteverdana {
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
.t1 {
	width: 80px;
}
.bordetop {
	border-top:1pt solid black;
}
.bordeabajo {
	border-bottom:1pt solid black;
}
.tabla {
	height:20px; width:100%
}
.espacio td {
	padding: 10px;
}
</style>
<table class="tabla fuenteverdana letra12 text-center espacio">
<tr>
	<td colspan="6">
		<b>{{$detalles[0]->personas_apellido}} {{$detalles[0]->personas_nombre}}</b>
		
	</td>
</tr>	
<tr>
	{{--<td>TARJETA:</td><td><b>{{Formatos::tarjeta($detalles[0]->numero_tarjeta)}}</b></td>--}}
	<td>NRO LIQ: <b>{{$detalles[0]->nro_liquidacion}}</b></td>
	<td>CUENTA: <b>{{$detalles[0]->numero_cuenta}}</b></td>
	<td>FECHA LIQ: <b>{{Formatos::fecha($detalles[0]->fecha_liquidacion)}}</b></td>
</tr>		
</table>

<table class="tabla fuenteverdana letra11 bordetop ">
	<tr>
		<td width="90px">CIERRE:</td>
		<td><b>{{Formatos::fecha($detalles[0]->fecha_cierre)}}</b></td>

		<td width="90px">PROX. CIERRE:</td>
		<td><b>{{Formatos::fecha($detalles[0]->fecha_cierre_prox)}}</b></td>

		<td width="90px">CIERRE ANT.:</td>
		<td><b>{{Formatos::fecha($detalles[0]->fecha_cierre_ant)}}</b></td>
	</tr>
	<tr>
		<td>VENCIMIENTO:</td>
		<td><b>{{Formatos::fecha($detalles[0]->fecha_vto)}}</b></td>
		
		<td>PROX. VENC.:</td>
		<td><b>{{Formatos::fecha($detalles[0]->fecha_vto_prox)}}</b></td>
		
		<td>VENC. ANT.:</td>
		<td><b>{{Formatos::fecha($detalles[0]->fecha_vto_ant)}}</b></td>
	</tr>
</table>
<table class="tabla fuenteverdana letra11 text-center bordetop espacio">
	<tr>
		<td class="t1">LÍMITES:</td>
		<td>
			CONTADO: <b>{{Formatos::moneda($detalles[0]->contado)}}</b>
		</td>
		<td>
			CUOTAS: <b>{{Formatos::moneda($detalles[0]->cuotas)}}</b>
		</td>
		<td>
			EFECTIVO: <b>{{Formatos::moneda($detalles[0]->efectivo)}}</b>
		</td>
	</tr>
</table>

<table class="tabla fuenteverdana letra11 bordetop espacio">
	<tr>
		<td>
			SALDO ANT.: <b>{{Formatos::moneda($detalles[0]->pmin_ant_imp)}}</b>
		</td>
		<td>
			PAGO ANT.: <b>{{Formatos::moneda($detalles[0]->pago_anterior)}}</b>
		</td>
		<td>
			INTERES: <b>{{Formatos::moneda($detalles[0]->interes)}}</b>
		</td>
		<td>
			SALDO ACT.: <b>{{Formatos::moneda($detalles[0]->saldo_actual_con)}}</b>
		</td>
		<td>
			PAGO MIN. ACT.: <b>{{Formatos::moneda($detalles[0]->pmin_actual)}}</b>
		</td>
	</tr>
</table>	

<?php $total = 0; ?>
<table class="fuenteverdana letra12 tabla bordetop">
<thead>
	<tr class="fondotitulo">
		<td><b>Fecha</b></td>
		<td><b>Autoriz.</b></td>
		<td><b>Cbte</b></td>
		<td><b>Referencia</b></td>
		<td><b>Cuota</b></td>
		<td><b>Ay.Económica</b></td>
		<td><b>Importe</b></td>
	</tr>
</thead>
<?php $ultima = -1; $nombre = ''; $subtotal = 0; ?>
	@foreach($consumos as $consumo)
	@if ($ultima <> $consumo->tarjeta and $ultima <> -1)
	<tr class="bordetop">
		<td colspan="3">Tarjeta: <b>{{Formatos::tarjeta($ultima)}}</b></td>
		<td colspan="3">Consumos de: <b>{{$nombre}}</b></td>
		<td align="right"><b>{{Formatos::moneda($subtotal)}}</b></td>
		<?php $subtotal = 0; ?>
	</tr>
	<tr>
		<td><br></td>
	</tr>
	@endif
	<tr>
		<td>{{Formatos::fecha($consumo->fecha)}}</td>
		<td>{{$consumo->autorizacion}}</td>
		@if ($consumo->cbte == 0)
			<td></td>
			<td>{{Formatos::capital($consumo->detalle)}}</td>
			<td></td>
		@else
			<td>{{$consumo->cbte}}</td>
			<td>{{Formatos::capital($consumo->nombre_fantasia)}}</td>
			<td>{{$consumo->cuota}}</td>
		@endif
		<td>{{$consumo->nro_prestamo_aye}}</td>
		<td align="right">{{Formatos::moneda($consumo->importe_compra)}}</td>
		
		<?php $ultima = $consumo->tarjeta;
				$nombre = $consumo->nombre;
				$subtotal = $subtotal + $consumo->importe_compra;
		 ?>
	</tr>
	<?php $total = $total + $consumo->importe_compra; ?>
	@endforeach
	<tr class="bordetop">
		<td colspan="3">Tarjeta: <b>{{Formatos::tarjeta($ultima)}}</b></td>
		<td colspan="3">Consumos de: <b>{{$nombre}}</b></td>
		<td align="right"><b>{{Formatos::moneda($subtotal)}}</b></td>
	</tr>

</table>
<br>
<table class="fuenteverdana letra11 text-right tabla">
	<tr>
		<td></td><td>TOTAL CONSUMOS:</td>
			<td width="100px"><b>{{Formatos::moneda($total)}}</b></td>
	</tr>
@if ($detalles[0]->interes * 1 > 0 )
	<tr>
		<td></td><td>INTERES LIQ. ANT.:</td>
			<td><b>{{Formatos::moneda($detalles[0]->interes)}}</b></td>
	</tr>
@endif
@if ($detalles[0]->sellado_consumo * 1 > 0 )
	<tr>
		<td></td><td>SELLADO CONSUMOS:</td>
			<td><b>{{Formatos::moneda($detalles[0]->sellado_consumo)}}</b></td>
	</tr>
@endif
@if ($detalles[0]->sellado_prestamo * 1 > 0 )
	<tr>
		<td></td><td>SELLADO PRESTAMOS:</td>
			<td><b>{{Formatos::moneda($detalles[0]->sellado_prestamo)}}</b></td>
	</tr>
@endif
@if ($detalles[0]->cargos_resumen * 1 > 0 )
	<tr>
		<td></td><td>CARGO POR RESUMEN:</td>
			<td><b>{{Formatos::moneda($detalles[0]->cargos_resumen)}}</b></td>
	</tr>
@endif
@if ($detalles[0]->seguro_vida * 1 > 0 )
	<tr>
		<td></td><td>SEGURO DE VIDA:</td>
			<td><b>{{Formatos::moneda($detalles[0]->seguro_vida)}}</b></td>
	</tr>	
@endif
<tr>
	<td><br></td>
</tr>
	<tr>
		<td width="500px"></td><td class="bordetop"><b>SALDO TOTAL:</b></td>
		<td class="bordetop"><b>{{Formatos::moneda($detalles[0]->saldo_actual_con)}}</b></td>
	</tr>	
</table>
<br><br>
