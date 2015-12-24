@extends ('cabecera')

@section ('content')

<style>
	.f_acordeon {
		width: 100%;
	}
	#acordeon {
		width: 100%;
	}
</style>
<br>
<br>
<nav class="navbar navbar-default">
	<div class="container-fluid letra11"> <div class="navbar-header">
	<p class="navbar-text text-center">
		<span class="glyphicon glyphicon-user"></span> <strong>{{$nombrecompleto}}</strong>
	</p>
	<p class="navbar-text text-center">
		<strong>Nro. {{$nrosocio}}</strong>
	</p>
	<p class="navbar-text text-center">
		<a href="/cambiarclave" class="ui-button ui-widget"><span class="glyphicon glyphicon-cog"></span> Cambiar contraseña</a>
	</p>
	<p class="navbar-text text-center">
		<a href="/salir"><span class="glyphicon glyphicon-off"></span> Cerrar Sesión</a>
	</p>
	</div> </div>
</nav>

<div class="letra11 f_acordeon">
	<div id="acordeon">
<?php $estado = Persona::socioEstado(); ?>
	@if ( $estado == 'A')
		@if (Auth::user()->nivel <> 2)
		<h3> <span class="badge">{{count($cuentas)}}</span> Ahorro Mutual Variable</h3>
		<div>
			@if(count($cuentas)==0)
				<div class="text-center">No tiene cuenta.</div>
			@else
				@include('usuario.servicio.amv')
			@endif
		</div>
		
		<h3> <span class="badge">{{count($terminos)}}</span> Ahorro Mutual a Término</h3>
		
			<div>
				@if(count($terminos)==0)
					<div class="text-center">No tiene Ahorro Mutual a Término.</div>
				@else
					@include('usuario.servicio.amt')
				@endif
			</div>
		
		<h3> <span class="badge">{{count($prestamos)}}</span> Ayuda Económica</h3>

			<div>
				@if(count($prestamos)==0)
					<div class="text-center">No tiene préstamos de Ayuda Económica.</div>
				@else
					@include('usuario.servicio.aye')
				@endif
			</div>

		<h3> <span class="badge">{{count($transitorias)}}</span> Ayuda Transitoria</h3>

			<div>
				@if(count($transitorias)==0)
					<div class="text-center">No tiene Ayuda Transitoria.</div>
				@else
					@include('usuario.servicio.transitoria')
				@endif
			</div>

		<h3> <span class="badge">{{count($cheques)}}</span> Cheques Rechazados</h3>
			<div>
				@if (count($cheques)==0)
					<div class="text-center">No tiene cheques rechazados.</div>
				@else
					@include('usuario.servicio.cheque')
				@endif
			</div>
		@endif
		@if ($liqusuario <> 'no')
			@if ($liqusuario=='noliq')
				<h3> <span class="badge">0</span> Liquidaciones de Tarjeta</h3>
				<div>
					<div class="text-center">No tiene liquidación emitida.</div>
				</div>
			@else
				<h3> <span class="badge">{{count($liqusuario)}}</span> Liquidaciones de Tarjeta</h3>
				<div>
					@include('usuario.servicio.liqusuario')
				</div>
			@endif
		@endif
	@endif
		<?php $estado = Persona::comercioEstado(); ?>
		@if ( $estado == 'A')
			@if ($liqcomercio <> 'no')
				<h3> <span class="badge">{{count($liqcomercio)}}</span> Liquidaciones Pendientes de Comercio</h3>
				<div>
					@include('usuario.servicio.liqcomercio')
				</div>
				<h3>Autorizaciones</h3>
				<div>
					@include('usuario.servicio.autorizaciones')
				</div>
			@endif
		@endif	</div>
	<br><br><br><br>

</div>


<div id="cambiarpass" title="Cambiar Contraseña">
	Actual: <input type="password" class="form-control input-sm"> <br>
	 Nueva: <input type="password" class="form-control input-sm"> <br>
	Confirmar: <input type="password" class="form-control input-sm"> <br>
</div>

<div id="detalleCuenta" title="Detalle de Cuenta" class="letra10">
	<div>
	<img src="" class="centro" id="cargando">
		<table id="tabla_detalle_cuenta" class="display no-footer dataTable letra12 table-striped">
		<thead>
        <tr>
        <th>indice</th><th>Fecha Acreditación</th><th>Tipo de Comprobante</th><th>Nro. Comprobante</th>
        <th>Débito</th><th>Crédito</th><th>Saldo</th>
        </tr>
        </thead>
        <tbody  id="resumen" >
        	
        </tbody>

		</table>
	</div>
</div>

@include('general.mensajecaja')
    
{{ Minify::javascript('/js/usuario.js') }}

@stop