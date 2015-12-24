<html>
	<head>
	<title>@yield('title', 'HOME BANKING')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/neoicon.png" />
{{ Cargar::stylesheet(array(
                          	'/css/bootstrap.css',
                          	'/css/bootstrap-theme.css',
                          	'/css/global.css',
                          	'/css/dataTables.jqueryui.css',
                          )) }}    
            
<style>

body {
    background-image: url("img/fondocubo4.png");
    background-repeat: repeat;
    background-size: 320px;
}

.centrado {
	position: relative;
	width: 500px;
 	
 	margin: auto;
 	top: 50px;
}
input:focus { 
    background-color: #FFFFCC;
    border: solid 3px;
    border-color: blue;
}
input{width: 100%;padding-left: 5px; 
	-webkit-border-radius: 5px;
       -moz-border-radius: 5px;
            border-radius: 5px;}
.fuente {
	font-style: normal !important;
}

.amarillo {background: yellow;}
.verde 	{background: green; color: white}
.rojo 	{background: red; color: white}

.tabla {width: 490px; height: 290px; padding: 5px;}

button {padding: 3px}
button:focus {border: solid 3px;  border-color: blue;}

.horizontal{
    float:left;
    padding-left:20px;
}

.mini {
	width: 20px
}

.tabla td {text-align: center;
	border-bottom: 15px solid transparent;
	left: 5px;
	padding-right: 10px;
}

.titulo {
	color: #333;
  background-color: #f5f5f5;
  border-color: #ddd;
}

.logo {position: fixed;
	   bottom: 2px; left: 2px;}
.logo img {width: 50%}

.operaciones {
	width: 100%;
	max-height: 400px;
}
.fondocolor {
	background: #b2b0bf !important;
}
</style>

</head>

<body>

<div class="ui-state-highlight cabeza titulo fondocolor" align="center"> {{Empresa::find(1)->nombre_legal}}</div>

<a href="http://www.neosistemassrl.com/" target="_blank" class="logo">
	<img src="img/neologo.png" alt="Grupo Neosistemas">
</a>

<div class="centrado sombra panel blanco redondear panel-primary">

	<div align='center' class="letra11 panel-heading fondocolor texto-sombra">
		AUTORIZACIONES DE TARJETAS
	</div>	
<br>
	<table align="center" class="tabla  letra12">

	<tbody>
		<tr>
			<td valign="top" ><br>NRO TARJETA:</td>
			<td>
				<div class="form-group has-error has-feedback">
   					<input type="text" class="form-control" id="nro_tarjeta" aria-describedby="nro_tarjetaStatus"
   					 data-toggle="tooltip" data-placement="top" title="">
  					<span id="tarjeta_icon" class="glyphicon glyphicon-pencil form-control-feedback" aria-hidden="true"></span>
  					<span id="estado_tarjeta"></span>
				</div>		
			</td>
			
		</tr>
		<tr>
			<td>SOCIO:</td>
			<td>
				<span id="nombre" class="form-control"></span>
			</td>
			
		</tr>
		<tr>
			<td valign="top" ><br>FORMA DE PAGO:</td>
			<td>
				<button id="contado" onclick="" class="form-control horizontal btn-default" style="width:120px">
					<span class="glyphicon glyphicon-file"></span> Contado</button>
				<button id="cuotas" class="derecha form-control btn-default" style="width:120px" onclick="">
					<span class="glyphicon glyphicon-duplicate"></span> Cuotas</button>
			</td>
		</tr>
		<tr>
			<td valign="top" ><br>CANT DE CUOTAS:</td>
			<td>
				<div class="form-group has-error has-feedback">
   					<input type="text" class="form-control" id="cant_cuotas" aria-describedby="cant_cuotasStatus">
  					<span id="cuotas_icon" class="glyphicon glyphicon-pencil form-control-feedback" aria-hidden="true"></span>
  					<span id="estado_cuotas"></span>
				</div>		
			</td>
		</tr>
		<tr>
			<td valign="top" ><br>IMPORTE TOTAL:</td>
			<td>
				<table><tr><td>
				<div class="form-group has-error has-feedback">
   					<input type="text" class="form-control" id="importe" aria-describedby="importeStatus">
  					<span id="importe_icon" class="glyphicon glyphicon-pencil form-control-feedback" aria-hidden="true"></span>
					<span id="estado_importe"></span>
				</div>
				</td><td valign="top">
				<button id="botoncaja" class="redondear btn-default" onclick="mostrarcaja()">
				<span class="glyphicon glyphicon-eye-open"></span> Cuotas </button>
				</td></tr></table>
			</td>
		</tr>
		<tr>
			<td valign="top" ><br>NRO CUPON:</td>
			<td>
				<div class="form-group has-error has-feedback">
   					<input type="text" class="form-control" id="cupon" aria-describedby="cuponStatus">
  					<span id="cupon_icon" class="glyphicon glyphicon-pencil form-control-feedback" aria-hidden="true"></span>
					<span id="estado_cupon"></span>
				</div>		
			</td>
		</tr>
	</tbody>
</table>
<table class="table letra11">
		<tr>
			<td align='center'><button class="f_amarillo redondear btn-default" onclick="autorizar()">
				<span class="glyphicon glyphicon-ok"></span> AUTORIZAR </button></td>
			<td align='center'><button class="f_rojo redondear btn-default" onclick="reiniciar()">
				<span class="glyphicon glyphicon-refresh"></span> REINICIAR </button></td>
			<td align='center'>
				<button class="f_cerrar redondear btn-default" onclick="cerrar()">
				<span class="glyphicon glyphicon-off"></span> CERRAR </button>
			</td>
		</tr>
		<tr><td></td>
				<td align="center">
					<button class="f_operaciones redondear btn-default" onclick="operaciones()">
					<span class="glyphicon glyphicon-tasks"></span> Operaciones del día </button>
				</td><td></td>
		</tr>
</table>

</div>

	@include('general.mensajecaja')
	@include('general.mostrarcaja')
	{{-- @include('comercio.operacionesdeldia') --}}

</html>
{{ Cargar::javascript(array(
                            '/js/jquery-1.11.2.min.js',
                            '/js/bootstrap.min.js',
                            '/js/posnet.js',
                            '/js/jquery-ui.min.js'
                            )) }}