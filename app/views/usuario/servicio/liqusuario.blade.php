<style>
fieldset {
	border: 1px solid #6e0101;
	Padding: 10px;
	font-size:12px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	background-color:#F4F4F4;
	-moz-border-radius: 3px;
	-webkit-border-radius: 3px;
	border-radius: 3px;
	behavior:url(border-radius.htc);
	width: 700px

}
legend {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 13px;
	color: #c70302;
	border: 1px solid #6e0101;	
	padding: 2px 6px;
	background-color:#FFFFFF;
	-moz-border-radius: 2px;
	-webkit-border-radius: 2px;
	width: 200px
}
</style>
<div align='center'>
<fieldset>
<legend class="text-center">Última Liquidación</legend>	

Límites:
 Contado: <b>{{Formatos::moneda($limites[0]->contado)}}</b>
 Cuotas: <b>{{Formatos::moneda($limites[0]->cuotas)}}</b>
 Adelanto: <b>{{Formatos::moneda($limites[0]->efectivo)}}</b>

<table class="table letra11 table-hover table-striped">
	 <thead>
	 	<tr>
	 		<th>Liquidación</th><th>Emisión</th><th>Vencimiento</th>
	 		<th>Importe</th><th>Fecha Pago</th><th>Importe Pagado</th>
	 		@if (!Formatos::esCelular())
	 			<th>Consumos</th>
	 		@endif
	 	</tr>
	 </thead>
	 <tbody>
		@foreach ($liqusuario as $liq)
		<tr>
			<td>{{$liq->nro_liquidacion}}</td>
			<td>{{Formatos::fecha($liq->fecha_liquidacion)}}</td>
			<td>{{Formatos::fecha($liq->fecha_vto_pmin_actual)}}</td>
			<td>{{Formatos::moneda($liq->saldo_actual_con)}}</td>
			<td>{{Formatos::fecha($liq->fecha_cobro)}}</td>
			<td>{{Formatos::moneda($liq->importe_cobro)}}</td>
			@if (!Formatos::esCelular())
				<td>
					<button class="ui-state-default ui-corner-all" onclick="verConsumosPro()">
					<span class="glyphicon glyphicon-search"></span> Detalle </button>
				</td>
			@endif
		</tr>
		@endforeach

	</tbody>
</table>
<hr>
@if (count($liqconsumos)>0)
	<div class="text-center">
		Consumos de la Próxima Liquidación 
		<button class="ui-state-default ui-corner-all" onclick="verSiguiente()">
		<span class="glyphicon glyphicon-search"></span> Detalle</button>
	</div>
@endif
</fieldset>
</div>