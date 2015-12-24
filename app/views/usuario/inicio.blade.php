@extends ('cabecera')

@section ('content')
<div class="container-fluid" id="fondo">

<style>
    @media screen and (min-width: 1040px) {
        html {
            background-image: url("img/fondocubo4.png");
            background-repeat: repeat;
            background-size: 320px;
        }
    }

.barra {
	width: 84%;
     left: 8%;
    right: 8%;
}

.contenido {
	width: 80%;
     left: 10%;
    right: 10%;
}

.logo {position: fixed;
	   bottom: 2px; left: 3px; right:30%;
}
.logo img {width: 30% !important; min-width: 200px !important}

</style>


<div class="grupo titulo total base-tabla medio">

	<div class="caja total" align="center">
    {{Empresa::find(1)->nombre_legal}}
	</div>
</div>

<div class="grupo titulo total base-tabla medio desde-tablet">

        <div class="caja tablet-35">
                <span class="icon-usuario"></span> 
                {{$nombrecompleto}}
        </div>
        <div class="caja tablet-25">
               <span class="icon-pin"></span> 
                Nro. {{$nrosocio}}
        </div>
        <div class="caja tablet-20">
                <a href="/cambiarclave" class="texto-blanco">
                 <span class="glyphicon glyphicon-cog"></span>
                  Cambiar contraseña
                </a>
        </div>
        <div class="caja tablet-20">
                <a href="/salir" class="texto-blanco">
                 <span class="glyphicon glyphicon-off"></span> 
                 Cerrar Sesión
                </a>
        </div>

</div>
<hr>
<div class="col-md-8 letra11 contenido" id="contenido" hidden>
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
		@endif
	</div>
	<br><br><br><br>

</div>

<script>
	$('#contenido').show();
</script>
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
        <th>indice</th><th>Acreditación</th><th>Detalle</th><th>Comprobante</th>
        <th>Débito</th><th>Crédito</th><th>Saldo</th>
        </tr>
        </thead>
        <tbody  id="resumen" class="letra10">

        </tbody>

		</table>
	</div>
</div>

@include('general.mensajecaja')

<a href="http://www.neosistemassrl.com/" target="_blank" class="logo">
	<img src="img/neologo.png" alt="Grupo Neosistemas">
</a>
    
{{ Minify::javascript('/js/usuario.js') }}
      </div>
@stop