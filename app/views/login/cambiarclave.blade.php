@extends ('cabecera')
<style>

    @media screen and (min-width: 1040px) {
        html {
            background-image: url("img/fondocubo4.png");
            background-repeat: repeat;
            background-size: 320px;
        }
    }
.logo {position: fixed;
	   bottom: 2px; left: 3px; right:30%;
}
.logo img {width: 30% !important; min-width: 200px !important}
</style>
@section ('content')

<div class="grupo titulo total base-tabla medio">
	<div class="caja total" align="center">
    {{Empresa::find(1)->nombre_legal}}
	</div>
</div>

<div align="center">
    <hr>
<div>
	<img src="" alt="" class="img-responsive">
	<div class="col-md-6 col-md-offset-3">
	<div class="panel panel-primary sombra">
		<div class="panel-heading colorfondo texto-sombra">CAMBIO DE CLAVE</div>
  			<div class="panel-body fondopanel">
				<form action="cambiarclave" method="post">

				 <div class="form-group form-group-sm col-md-8 col-md-offset-2">

					@if(isset($erroractual))
						<img src="img/alert.png"> <br>
						<span class="bg-danger">{{ $erroractual }}</span><br>
					@endif
  					
				    <label for="claveactual">Clave Actual</label>
				    <input type="password" class="form-control input-sm" id="claveactual" name="claveactual" placeholder="Clave Actual..."><br>

					@if(isset($errornueva))
						<img src="img/alert.png"> <br>
						<span class="bg-danger">{{ $errornueva }}</span><br>
					@endif

				    <label for="nuevaclave">Nueva Clave</label>
				    <input type="password" class="form-control input-sm" id="nuevaclave"
				     name="nuevaclave" placeholder="Nueva Clave..."><br>
					
					@if(isset($errorconfirma))
						<img src="img/alert.png"> <br>
						<span class="bg-danger">{{ $errorconfirma }}</span><br>
					@endif

				    <label for="confirmarclave">Confirmar Clave</label>
				    <input type="password" class="form-control input-sm" id=""
				     name="confirmarclave" placeholder="Confirmar Clave..."><br>
				  	  
					<button type="submit" class="btn btn-primary btn-sm colorfondo texto-sombra izquierda">
					 	<span class="glyphicon glyphicon-ok"></span> Cambiar
					</button> 
					@if (Auth::user()->estado==0)
					<a href="/salir" class="btn btn-primary btn-sm colorfondo texto-sombra derecha">
					 	<span class="glyphicon glyphicon-remove"></span> Cancelar
					</a>
					@else
					<a href="/autorizaciones" class="btn btn-primary btn-sm colorfondo texto-sombra derecha">
					 	<span class="glyphicon glyphicon-remove"></span> Cancelar
					</a>
					@endif
				  </div>

				</form>
			</div>
		</div>
	</div>
	</div>

</div>

</div>
<a href="http://www.neosistemassrl.com/" target="_blank" class="logo">
	<img src="img/neologo.png" alt="Grupo Neosistemas">
</a>

@stop