<html>
	<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
		{{ Cargar::stylesheet(array(
		                          	'/css/bootstrap.css',
		                          	'/css/bootstrap-theme.css',
		                          	'/css/global.css',
		                          	'/css/dataTables.jqueryui.css',
		                          )) }}
	<style>
		input:focus { 
		    		background-color: #FFFFCC;
					}
		.horizontal{
    				float:left;
    				padding-left:20px;
					}
	</style>
	</head>

<body>
<div align="center">
<div class="row">
<div class="col-md-1">
<div class="panel panel-primary sombra">
<div class="panel-heading">AUTORIZACIONES DE TARJETAS 
		<span id="salir" class="derecha glyphicon glyphicon-off" onclick="cerrar()"></span>
</div>
<div class="panel-body">

				<label for="nro_tarjeta">NRO TARJETA:</label>
				<div class="form-group has-error has-feedback">
   					<input type="number" class="form-control" id="nro_tarjeta" aria-describedby="nro_tarjetaStatus"
   					 data-toggle="tooltip" data-placement="top" title="">
  					<span id="tarjeta_icon" class="glyphicon glyphicon-pencil form-control-feedback" aria-hidden="true"></span>
  					<span id="estado_tarjeta"></span>
				</div>		


			<label for="nombre">SOCIO:</label>
			<p>
				<span id="nombre" class="form-control"></span>
			</p>

			<label for="fpago">FORMA DE PAGO:</label>
			<div id="fpago">
			<p>
				<button id="contado" class="form-control btn-default">
					<span class="glyphicon glyphicon-file"></span> Contado</button>
				</p><p>
				<button id="cuotas" class="form-control btn-default">
					<span class="glyphicon glyphicon-duplicate"></span> Cuotas</button>
				</p>
			</div>

				<label for="cant_cuotas">CANT DE CUOTAS:</label>
				<div class="form-group has-error has-feedback">
   					<input type="number" class="form-control" id="cant_cuotas" aria-describedby="cant_cuotasStatus">
  					<span id="cuotas_icon" class="glyphicon glyphicon-pencil form-control-feedback" aria-hidden="true"></span>
  					<span id="estado_cuotas"></span>
				</div>		

				<label for="importe">IMPORTE:</label>
				<div class="form-group has-error has-feedback">
   					<input type="number" class="form-control" id="importe" aria-describedby="importeStatus">
  					<span id="importe_icon" class="glyphicon glyphicon-pencil form-control-feedback" aria-hidden="true"></span>
					<span id="estado_importe"></span>
				</div>		

				<label for="cupon">NRO CUPON:</label>
				<div class="form-group has-error has-feedback">
   					<input type="number" class="form-control" id="cupon" aria-describedby="cuponStatus">
  					<span id="cupon_icon" class="glyphicon glyphicon-pencil form-control-feedback" aria-hidden="true"></span>
					<span id="estado_cupon"></span>
				</div>

			<button class="f_amarillo redondear btn-default izquierda" onclick="autorizar()">
				<span class="glyphicon glyphicon-ok"></span> AUTORIZAR </button>
					
			<button class="f_rojo redondear btn-default derecha" onclick="reiniciar()">
				<span class="glyphicon glyphicon-refresh"></span> REINICIAR </button>
						<br><hr>
						<button class="f_operaciones redondear btn-default" onclick="operaciones()">
					<span class="glyphicon glyphicon-tasks"></span> Operaciones del día </button>
</div>
</div>
</div>
</div>
</div>

@include('general.mensajecaja')

</body>
</html>

{{ Cargar::javascript(array(
                            '/js/jquery-1.11.2.min.js',
                            '/js/bootstrap.min.js',
                            '/js/posnet.js',
                            '/js/jquery-ui.min.js',
                            )) }}

