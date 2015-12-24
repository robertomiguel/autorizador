<span class="letra10">Nro. Comercio: <strong>{{Persona::numeroComercio();}}</strong> <br>
			Nombre de Fantasía: <strong>{{Persona::nombreComercio();}}</strong></span>
<table class="table letra11 table-hover table-striped">
 <thead>
 	<tr>
 		<th>Nro. Liquidación</th><th>Fecha Liquidación</th><th>Fecha Vencimiento</th>
 		<th>Monto</th>
 	</tr>
 </thead>
 <tbody>
	@foreach ($liqcomercio as $liq)
	<tr>
		<td>{{$liq->nro_liquidacion}}</td>
		<td>{{Formatos::fecha($liq->fecha_liquidacion)}}</td>
		<td>{{Formatos::fecha($liq->fecha_vto_liquidacion)}}</td>
		<td>{{Formatos::moneda($liq->monto_liquidacion)}}</td>
	</tr>
	<?php 
	$ret = TarjetaComercio::retenciones($liq->monto_liquidacion);
	?>
	<tr>
		<td colspan="4" align="center"><b>RETENCIONES</b></td>
	</tr>
	<tr>
		<td><b>IVA</b></td><td><b>GANANCIAS</b></td><td><b>IIBB</b></td><td><b>TOTAL</b></td>
	</tr>
	<tr>
		<td>{{Formatos::moneda($ret[1])}}</td>
		<td>{{Formatos::moneda($ret[2])}}</td>
		<td>{{Formatos::moneda($ret[3])}}</td>
		<td>{{Formatos::moneda($ret[4])}}</td>
	</tr>
	<tr>
		<td colspan="3" align="right"><b>TOTAL A COBRAR:</b></td>
		<td><b>$ {{Formatos::moneda($liq->monto_liquidacion - $ret[4])}}</b></td>
	</tr>
	@endforeach
 </tbody>
</table>				
