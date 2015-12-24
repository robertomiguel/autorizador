<style>
.modal-dialog, .modal-content {
    max-height: 90%;
}

.modal-body {
    max-height: calc(100% - 120px);
    overflow-y: scroll;
}
.latabla {
        font-family: "Times New Roman", Times, serif;
}
.info {
	    font-family: "Times New Roman", Times, serif;
}
.fuenteroman {
        font-family: "Times New Roman";
}
.fuenteverdana {
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
</style>
<?php $total = 0; ?>
<table class="table letra12 table-hover table-striped fuenteverdana">
<thead>
	<tr>
		<td align="center" width="120px"><b>Fecha de Compra</b></td>
		<td align="center"><b>Comercio</b></td>
		<td align="center" width="50px"><b>Cuota</b></td>
		<td align="center"><b>Importe</b></td>
	</tr>
</thead>
	@foreach ($consumos as $consumo)
	<tr>
		<td align="center">
			{{Formatos::fecha($consumo->fecha_consumo)}}
		</td>
		<td align="left">
			{{Formatos::capital($consumo->com_nombre_fantasia)}}
		</td>
		<td align="center">
			{{$consumo->cuota_nro.'/'.$consumo->cuota_total}}
		</td>
		<td align="right">
			{{Formatos::moneda($consumo->importe_compra)}}
		</td>
	</tr>
		<?php $total = $total + $consumo->importe_compra ?>
	@endforeach
</table>

<div class="text-right fuenteroman letra12">
	TOTAL: <b>{{Formatos::moneda($total)}}**</b>
</div>
<div class="text-left fuenteroman letra10">
	(**) no incluye gastos administrativos.
</div>

{{--<script>
	$('#mensajepie').html("<span class='izquierda'>Total: $ {{Formatos::moneda($total)}}</span><br>"+
						  "<span class='info'>No incluye gastos administrativos, ni intereses por pago fuera de término</span>");
</script>--}}