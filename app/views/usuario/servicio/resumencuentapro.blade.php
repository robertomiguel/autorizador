<style>
.modal-dialog, .modal-content {
    max-height: 90%;
    width: 900px;
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
<?php $acumula = 0;
	  $ini = parse_ini_file("../app/config/tipo_cbte.ini");
 ?>
<div class="fuenteverdana letra10">
	
<table id="tabla" class="tabla fuenteverdana letra11 hover">
<thead>
	<tr>
		<td>Fecha</td>
		<td>Detalle</td>
		<td>Comprobante</td>
		<td>Débito</td>
		<td>Crédito</td>
		<td>Pendiente</td>
		<td>Saldo</td>
	</tr>
</thead>
	<tbody id="cuerpo">
		@foreach ($detalles as $d)
			<tr data-toggle="tooltip" data-placement="top" title="Ref: {{$d->referencia}}">
				<td>{{Formatos::fecha($d->fecha)}}</td>
				<td>{{$ini[$d->tipo_cbte]}}</td>
				<td>{{$d->cbte}}</td>
				<td>{{Formatos::moneda($d->debito)}}</td>
				<td>{{Formatos::moneda($d->credito)}}</td>
				<td>{{Formatos::moneda($d->monto_no_acred)}}</td>
				<?php $acumula = $acumula +  $d->saldo; ?>
				<td>{{Formatos::moneda($acumula)}}</td>
			</tr>
		@endforeach
	</tbody>
</table>

</div>

<script>
var tbody = $('#cuerpo');
tbody.html($('tr',tbody).get().reverse());

	$('#tabla').dataTable({
     	{{--"scrollY" :  400,--}}
			"scrollCollapse": true,
			"ordering": false,
			"jQueryUI": true,
            "bDestroy": true,
            "pagingType": "full_numbers"
        });
</script>