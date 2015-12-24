<?php

class TarjetasCupones extends Eloquent {

	protected $table = 'tarjetas_cupones';
	protected $primaryKey = 'numero_cupon';
	public $timestamps = false;

	static public function validarCupon($nro_cupon) {
		//$codigo_comercio = Persona::numeroComercio();

		$sql = "SELECT tarjetas_cupones.numero_cupon 
				  FROM tarjetas_cupones
				 WHERE tarjetas_cupones.nro_transaccion 	= 0
				   AND isNull(tarjetas_cupones.anulado,0) 	= 0
				   AND tarjetas_cupones.entregado 			= 1
				   AND tarjetas_cupones.numero_cupon 		= $nro_cupon
				   AND tarjetas_cupones.numero_cupon NOT IN ( SELECT hb_autorizaciones.numero_cupon
								 							    FROM hb_autorizaciones )";

		$libre = DB::select($sql);

		if ( count($libre) <= 0) { return 0; }

		return count($libre);
	}
}