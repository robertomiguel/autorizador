<?php 
class SocioControlador extends BaseController {
	public function postDetalleCuentaPro(){
		
		$datos = MovimientosAhorro::resumenCuentaPro($cuenta = Input::get('cuenta'));

		return View::make('usuario.servicio.resumencuentapro')->with('detalles',$datos);
	}
	public function postDetalleCuenta(){
		$cuenta = Input::get('cuenta');

		$cuentas = cuentasAhorro::socioCuentas();

		$check = 0;

		foreach ($cuentas as $ncuenta) {
			if ($ncuenta->numero_cuenta==$cuenta) {$check=1;}
		}
		if ($check==0) {
			return 'No disponible.';
		}

		$sql = "
		SELECT
		movimientos_ahorro.fecha_acreditacion,
        movimientos_ahorro.tipo_cbte,
        CASE 
        WHEN (movimientos_ahorro.tipo_cbte % 2) = 0 THEN movimientos_ahorro.monto
        ELSE movimientos_ahorro.monto * -1
        END as saldo
		FROM movimientos_ahorro
			join cuentas_ahorro
				on cuentas_ahorro.numero_cuenta = movimientos_ahorro.numero_cuenta

		  WHERE	movimientos_ahorro.nro_empresa 		= 1
		    AND movimientos_ahorro.nro_sucursal 	= 1
		    AND movimientos_ahorro.numero_cuenta 	= $cuenta
		    AND cuentas_ahorro.cuenta_anulada		= 0
		";
		$detalle = DB::select($sql);

		if (count($detalle)<=0){
			return 'No tiene movimientos.';
		}

		$i = 0;
		$ini = parse_ini_file("../app/config/tipo_cbte.ini");
		$saldo = 0;
		foreach ($detalle as $mov) {
			$saldo = $saldo + $mov->saldo;
			$informe[$i] = array(
				'fecha' 	=> $mov->fecha_acreditacion,
				'concepto' 	=> $ini[$mov->tipo_cbte],
				'importe' 	=> $mov->saldo,
				'saldo'		=> $saldo
				);
			$i = $i + 1;
		}

		return View::make('usuario.servicio.detallecuenta')
								->with('informe',json_encode(array_reverse($informe)));
	}
	public function postConsumosActuales() {
		$datos = TarjetasConsumos::consumos();
		return View::make('usuario.servicio.proxresumen')->with('consumos', $datos);
	}
		public function postConsumosActualesPro() {
		$detalles = TarjetaUsuarioMov::detalleConsumo();
		$consumos = TarjetaUsuarioMov::detalleListado();
		return View::make('usuario.servicio.resumendetalle')
				->with('detalles', $detalles)
				->with('consumos', $consumos);
	}

	public function postConsumosSiguientes() {

		$numero_cuenta = TarjetaCuenta::numeroCuenta();

		$sql = "
		select max(tum.nro_liquidacion) as ultima_liq
		 from tarjetas_usuarios_mov tum
            where tum.nro_empresa   = 1
            and tum.nro_sucursal    = 1
			and tum.codigo_tarjeta  = 1
			and tum.numero_cuenta   = $numero_cuenta
			and tum.tipo_cbte_liquidacion = 31
		";

		$datos = DB::select($sql);
		
		$nro_liquidacion = $datos[0]->ultima_liq;
		
		$sql = "
		Select
			fecha_consumo,	com_nombre_fantasia, cuota_nro, cuota_total, importe_compra
  		From tarjetas_consumos join tarjetas_cuentas On 
		  	 ( tarjetas_cuentas.nro_empresa  = tarjetas_consumos.nro_empresa And
			 tarjetas_cuentas.nro_sucursal   = tarjetas_consumos.nro_sucursal And  
			 tarjetas_cuentas.codigo_tarjeta = tarjetas_consumos.codigo_tarjeta And  
			 tarjetas_cuentas.numero_cuenta  = tarjetas_consumos.numero_cuenta	)
			join personas on personas.com_numero_comercio = codigo_comercio
		Where tarjetas_consumos.nro_empresa  	 				 = 1
			And tarjetas_consumos.nro_sucursal 	 				 = 1
			And tarjetas_consumos.codigo_tarjeta 				 = 1
			And tarjetas_consumos.codigo_liquidacion = (select top 1 codigo_liquidacion from tarjetas_consumos
		Where nro_liquidacion = '$nro_liquidacion')  + 1
			And tarjetas_consumos.numero_cuenta	= $numero_cuenta
			And IsNull( tarjetas_consumos.compra_anulada, 0) = 0
			And IsNull( tarjetas_consumos.liquidada	  , 0) = 0	
			And tarjetas_cuentas.codigo_ciclo = (select codigo_ciclo from tarjetas_cuentas where numero_cuenta = $numero_cuenta)
			order by 1 asc
		";
		$datos = DB::select($sql);
		return View::make('usuario.servicio.proxresumen')->with('consumos', $datos);
	}
}