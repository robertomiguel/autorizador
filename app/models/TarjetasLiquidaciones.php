<?php

class TarjetasLiquidaciones extends Eloquent {

	protected $table 		= 'tarjetas_liquidaciones';
	protected $primaryKey 	= 'codigo_liquidacion';
	public $timestamps 		= false;

	static public function validarLiquidaciones($numero_cuenta, $cuotas) {

	$sql = "
			SELECT fecha_vto
			  FROM tarjetas_liquidaciones
			 WHERE nro_empresa		= 1
			   AND nro_sucursal		= 1
			   AND codigo_tarjeta	= 1
			   AND numero_ciclo		= (
										SELECT tarjetas_cuentas.codigo_ciclo
										  FROM tarjetas_cuentas
										 WHERE tarjetas_cuentas.nro_empresa 	= 1
										   AND tarjetas_cuentas.nro_sucursal 	= 1
										   AND tarjetas_cuentas.codigo_tarjeta  = 1
										   AND tarjetas_cuentas.numero_cuenta	= $numero_cuenta
										)
			   AND fecha_vto_ant < DATEADD(MONTH, 10, GETDATE())
			   AND fecha_vto BETWEEN GETDATE() AND DATEADD(MONTH, $cuotas, GETDATE())";

		$fechas = DB::select($sql);

		if (count($fechas) == $cuotas) {
		return true;
	}

	return false;
	}
}