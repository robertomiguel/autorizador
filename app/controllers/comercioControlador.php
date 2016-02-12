<?php 
class ComercioControlador extends BaseController {

    public function getIndex()
	 {
	  /*  	if(isset($_COOKIE['nro'])) {
	    		$pin = $_COOKIE['nro'];
		} else {
			$pin = '';
		}

    	if ( $pin <> ComercioControlador::pin()) {
    		return Redirect::to('/usuario');
    	}*/

 		//unset($_COOKIE['nro']);
  		//setcookie('nro', '', time() - 3600);

    	if (Formatos::esCelular()){
    		return View::make('comercio.celular.autorizacion');
    	} else {
    		return View::make('comercio.autorizacion');
    	}
	}

/*	static public function estado() {

		return TarjetaComercio::find(Persona::numeroComercio())->estado_comercio;
	} */
/*
	static public function pin() {
		return TarjetaComercio::find(Persona::numeroComercio())->pin_comercio;
	}
*/

	public function postValidarTarjeta(){
		$nro_tarjeta  = str_replace('-', '', Input::get('nro_tarjeta'));
		$datos = TarjetasPlasticos::validarTarjeta($nro_tarjeta);
		if (count($datos)==0) {return 0;}
		$fechahoy = Formatos::fechaHoraActual();
		$nombre = Persona::find($datos[0]->nro_persona)->apellido.' '.Persona::find($datos[0]->nro_persona)->nombre;
		//$nombre 			= Persona::find($datos[0]->nro_persona)->apellido;//Formatos::capital($datos[0]->nombre);
		$tarjeta_estado 	= $datos[0]->tarjeta_estado;
		$socio_estado 		= $datos[0]->socio_estado;
		$cuenta_estado 		= $datos[0]->cuenta_estado;
		
		$fecha_vencimiento 	= $datos[0]->fecha_vencimiento;
		$fecha_inicio 		= $datos[0]->fecha_inicio;

		if ($tarjeta_estado <> 0) {
			switch ($tarjeta_estado) {
				case '1':
					return $nombre.'|'.'La tarjeta está inhabilitada.';
					break;
				case '2':
					return $nombre.'|'.'La tarjeta fué dada de baja por renovación.';
					break;
				case '5':
					return $nombre.'|'.'La tarjeta está bloqueada.';
					break;
				case '6':
					return $nombre.'|'.'La tarjeta fué anulada.';
					break;
				case '9':
					return $nombre.'|'.'La tarjeta fué dada de baja.';
					break;
				default:
					return $nombre.'|'.'La tarjeta no puede operar. Consulte con el ente emisor de la tarjeta.';
					break;
			}
		}
		if ($socio_estado <> 'A'){
			return $nombre.'|'.'El socio no está activo';
		}
		if ($cuenta_estado <> 0 && $cuenta_estado <> 4 ){
			switch ($tarjeta_estado) {
				case '1':
					return $nombre.'|'.'La Cuenta está inhabilitada.';
					break;
				case '2':
					return $nombre.'|'.'La Cuenta fué dada de baja.';
					break;
				case '5':
					return $nombre.'|'.'La Cuenta está bloqueada.';
					break;
				case '6':
					return $nombre.'|'.'La Cuenta está en judicial.';
					break;
				default:
					return $nombre.'|'.'La Cuenta no puede operar. Consulte con el ente emisor de la tarjeta.';
					break;
			}
			
		}
		if ($fecha_vencimiento < $fechahoy){
			return $nombre.'|'.'Tarjeta Vencida fck';
		}
		if ($fecha_inicio > $fechahoy){
			return $nombre.'|'.'Fecha de Inicio posterior a hoy';
		}
		return $nombre.'|'.'Correcto';
	}

	public function postValidarCuotas(){
		$cuotas  = Input::get('cuotas') * 1;
		$numero_tarjeta = Input::get('numero_tarjeta');
		$planes = TarjetasPlanes::validarCuotas($cuotas) * 1;
		if ($planes==0){
			return 'Supera el límite de cuotas';
		}

		$numero_cuenta = TarjetasPlasticos::cuenta($numero_tarjeta);

		if (!TarjetasLiquidaciones::validarLiquidaciones($numero_cuenta, $cuotas)) {
			return 'Faltan cargar Períodos para éstas cuotas';
		}
		return 'Correcto';
	}

	public function postValidarImporte(){
		$nro_tarjeta  = str_replace('-', '', Input::get('nro_tarjeta'));
		$importe  = str_replace(',', '.', Input::get('importe')) * 1;

		if ($importe < 10 ) {return 'Monto Mímino $10.-';}
		if ($importe > 1000000 ) {return 'Monto Máximo $1.000.000.-';}

		$pago  = Input::get('pago') * 1;
		if ($pago==0 || $pago>2) { return 'Forma de pago incorrecta';}
		if ($pago==1) { $pago = 'contado';}
		if ($pago==2) { $pago = 'cuotas';}
		
		try {
		
		$nro_socio = TarjetasPlasticos::find($nro_tarjeta)->numero_socio;
		$numero_cuenta = TarjetasPlasticos::find($nro_tarjeta)->numero_cuenta;

		} catch (Exception $e) {
			
			return 'Falta Validar Nro. de Tarjeta';
		}
		
		
		$datos = Socio::scoring($nro_socio);
		$pendiente = Socio::consumosPendiente($numero_cuenta,$pago);

		$saldo_contado = $datos[0]->saldo_contado - $pendiente;
		$saldo_cuotas = $datos[0]->saldo_cuotas - $pendiente;

		if ($pago=='contado'){ $resto = $saldo_contado - $importe; }
		if ($pago=='cuotas'){ $resto = $saldo_cuotas - $importe; }

		if ($resto >= 0 ) {
			return 'Correcto';
		} else {
			return 'No tiene saldo suficiente';			
		}
		
	}

	public function postValidarCupon(){
		$cupon  = Input::get('cupon');

		if (TarjetasCupones::validarCupon($cupon) > 0){
			return 'Correcto';
		}

		return 'Cupón no válido, o ya fué usado';
	}

	static public function validar() {
		$nro_tarjeta 	= str_replace('-', '', Input::get('nro_tarjeta'));
		$pago  			= Input::get('pago');
		$cuotas  		= Input::get('cuotas') * 1;
		$importe  		= str_replace(',', '.', Input::get('importe'));
		$cupon  		= Input::get('cupon');

		$fechahoy = Formatos::fechaHoraActual();
		
	//--- Validar tarjeta ---
		$datos = TarjetasPlasticos::validarTarjeta($nro_tarjeta);
		if (count($datos)==0) {return 'Nro. de Tarjeta Inválido';}
		$fechahoy = Formatos::fechaHoraActual();
		
		//$nombre 			= Formatos::capital($datos[0]->nombre);
		$tarjeta_estado 	= $datos[0]->tarjeta_estado;
		$socio_estado 		= $datos[0]->socio_estado;
		$cuenta_estado 		= $datos[0]->cuenta_estado;
		
		$fecha_vencimiento 	= $datos[0]->fecha_vencimiento;
		$fecha_inicio 		= $datos[0]->fecha_inicio;

		if ($tarjeta_estado <> 0) {
			return 'La tarjeta no está Activa';
		}
		if ($socio_estado <> 'A'){
			return 'El socio no está activo';
		}

		if ($cuenta_estado <> 0 && $cuenta_estado <> 4 ){
			return 'Cuenta inactiva';
		}
		$dt = new DateTime($fecha_vencimiento);
		if (date_format($dt,'d-m-Y') < $fechahoy){
			return 'Tarjeta Vencida ' . $fechahoy;
		}
		if ($fecha_inicio > $fechahoy){
			return 'Fecha de Inicio posterior a hoy';
		}
		
	//--- Validar cantidad de cuotas
		if ($pago==0 || $pago>2) { return 'Forma de pago incorrecta';}
		if ($pago==1) { $pago = 'contado';}
		if ($pago==2) { $pago = 'cuotas';}

		if ($pago=='cuotas' && $cuotas <= 1 )
			{ return 'Para pago en Cuotas, el mínimo de cuotas es 2'; }
		if ($pago=='contado' && $cuotas <> 1 )
			{ return 'Para pago de Contado, la cantidad de cuotas debe ser 1'; }
		$planes = TarjetasPlanes::validarCuotas($cuotas) * 1;
		if ($planes <= 0) {
			return 'Supera el límite de cuotas';
		}

	//--- Validar períodos de cuotas
		$numero_cuenta = TarjetasPlasticos::cuenta($nro_tarjeta);
		if (!TarjetasLiquidaciones::validarLiquidaciones($numero_cuenta, $cuotas)) {
			return 'Faltan cargar Períodos para éstas cuotas';
		}

	//--- Validar importe
		if ($importe * 1 <= 0 ) {return 'Importe Inválido';}
		
		try {
		
		$nro_socio = TarjetasPlasticos::find($nro_tarjeta)->numero_socio;
		$numero_cuenta = TarjetasPlasticos::find($nro_tarjeta)->numero_cuenta;

		} catch (Exception $e) {
			
			return 'Falta Validar Nro. de Tarjeta';
		}
		
		$datos = Socio::scoring($nro_socio);
		$pendiente = Socio::consumosPendiente($numero_cuenta,$pago);
		
		$saldo_contado = $datos[0]->saldo_contado - $pendiente;
		$saldo_cuotas = $datos[0]->saldo_cuotas - $pendiente;

		if ($pago=='contado'){ $resto = $saldo_contado - $importe; }
		if ($pago=='cuotas'){ $resto = $saldo_cuotas - $importe; }

		if ($resto < 0 ) {
			return 'No tiene saldo suficiente';			
		}

	//--- Validar cupón
		if ($cupon * 1 <= 0 ) {return 'Nro de Cupón Inválido';}
		if (TarjetasCupones::validarCupon($cupon) <= 0){
		return 'Cupón no válido, o ya fué usado';
		}

		//$codigo_autorizacion = ParametrosTarjeta::codigoAutorizacion($pago);
		$codigo_comercio = Persona::numeroComercio();

		DB::beginTransaction();

	  	$codigo_autorizacion_mostrar = ParametrosTarjeta::codigoAutorizacion($pago);
	  	if ($codigo_autorizacion_mostrar=='error'){
	  		return 'No se pudo generar el Código de Autorización.';
	  	}
		$cod_aut = explode('/', $codigo_autorizacion_mostrar );
	  
		$codigo_autorizacion = $cod_aut[0];
		$codigo_autorizacion_add = $cod_aut[1];
		  
		$sql = "
		      insert into hb_autorizaciones
		      (
		        nro_empresa,		        nro_sucursal,
		        codigo_tarjeta,		        codigo_autorizacion,
		        codigo_autorizacion_add,    numero_cuenta,

		        numero_tarjeta,		        fecha_autorizacion,
		        importe,		        	cant_cuotas,
		        numero_cupon,		        codigo_comercio
		      )
		      values
		      (
		        1,		        			1,
		        1,		        			$codigo_autorizacion,
		        '$codigo_autorizacion_add',	'$numero_cuenta',
		        
		        '$nro_tarjeta',		        '$fechahoy',
		        $importe,		        	$cuotas,
		        $cupon,		        		$codigo_comercio
		      )
		    ";

		    try {
				$grabar = DB::insert($sql);
		    } catch (Exception $e) {
				DB::rollback();
				return 'No se pudo finalizar la operación. Reintentar.';
		    }
		    if ($grabar<>1){
		    	DB::rollback();
		    	return 'No se pudo finalizar la operación. Reintentar.';
		    }

			DB::commit();
		return 'Código de Autorización: '.$codigo_autorizacion_mostrar;
	}

	public function postOperaciones(){
		$nro_comercio = Persona::numeroComercio();
    	$fechahoy = Formatos::fechaActual();
		$datos = HBAutorizaciones::operacionesDelDia($nro_comercio,$fechahoy);
		if (count($datos)<=0) {
			return 'No hay operaciones.';
		}
		return View::make('comercio.operacionesdeldia')
							->with('operaciones',$datos);
	}

	public function getImprimirOperaciones(){
		$nro_comercio = Persona::numeroComercio();
    	$fechahoy = Formatos::fechaActual();
		$operaciones = HBAutorizaciones::operacionesDelDia($nro_comercio,$fechahoy);
		if (count($operaciones)<=0) {
			return 'No hay operaciones.';
		}
		
		$pdf = App::make('dompdf');
		$pdf->loadHTML(View::make('comercio.imprimiraut')->with('operaciones',$operaciones));
		
		//$pdf = $dompdf->get_canvas()->get_cpdf();
		return $pdf->stream('operaciones_'.Formatos::fechaActual());
		
		//return View::make('comercio.imprimiraut')->with('operaciones',$operaciones);
		//return $pdf->download('operaciones_'.Formatos::fechaActual());

	}


	public function postDetallecompra(){

		if ( Input::get('cuotas') * 1 < 2 ) {
			return 'Mínimo de cuotas 2';
		}

		$tarjeta  = str_pad( Input::get('tarjeta').''		, 16, '0', STR_PAD_LEFT );
		$comercio = str_pad( Persona::numeroComercio().''	, 13, '0', STR_PAD_LEFT );
		$cuotas   = str_pad( Input::get('cuotas' ).''		,  2, '0', STR_PAD_LEFT );
		$monto 	  = str_pad( Input::get('monto'  ).''		, 12, '0', STR_PAD_LEFT );

		$parametro = $tarjeta.$comercio.$cuotas.$monto;

		$comando = 'c:\neoweb\ivrweb\ivrweb.exe ' . $parametro;
		//$usr = exec('whoami'); . ' - USR: ' . $usr
		Log::info('CMD: ' . $comando);
		//$r = shell_exec($comando);
		$r = shell_exec($comando);

		if (!file_exists('c:/neoweb/ivrweb/detalles/'.$tarjeta.'.xml')) {
			Log::error('NO EXISTE: '.'d:/ivrweb/detalles/'.$tarjeta.'.xml');
			return 'no se puede leer detalle';
		}

    	$xml = simplexml_load_file('c:/neoweb/ivrweb/detalles/'.$tarjeta.'.xml');

    	unlink('c:/neoweb/ivrweb/detalles/'.$tarjeta.'.xml');

    	$nroPlan = TarjetasPlanes::nroPlan($cuotas * 1);
    	$promo = TarjetaPlanPromocion::buscarPromo($nroPlan);

		return View::make('comercio.detallecompra')->with('detalle',$xml)->with('promo',$promo);
	}
}