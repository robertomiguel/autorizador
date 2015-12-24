<?php

class TarjetaComercioMov extends Eloquent {

	protected $table = 'tarjetas_comercios_mov';
	protected $primaryKey = 'nro_liquidacion';
	public $timestamps = false;

	static public function liqcomercio() {
		$codigo_comercio = Persona::numeroComercio();
		
		$sql = "SELECT tarjetas_comercios_mov.nro_liquidacion,
					   tarjetas_comercios_mov.monto_liquidacion,
					   tarjetas_comercios_mov.fecha_liquidacion,
					   tarjetas_comercios_mov.fecha_vto_liquidacion
    			  FROM tarjetas_comercios_mov
				  JOIN personas ON
				  	   ( tarjetas_comercios_mov.codigo_comercio = personas.com_numero_comercio
					   AND personas.es_comercio = 1 )
	   			 WHERE tarjetas_comercios_mov.nro_empresa 			= 1
	    		   AND tarjetas_comercios_mov.nro_sucursal 			= 1
	    		   AND tarjetas_comercios_mov.codigo_tarjeta 		= 1
				   AND tarjetas_comercios_mov.tipo_cbte_liquidacion	= 27
				   AND IsNull( tarjetas_comercios_mov.liquidacion_cancelada,0 ) = 0
				   AND IsNull( tarjetas_comercios_mov.anulada,0 ) = 0
				   AND codigo_comercio = $codigo_comercio
			     ORDER BY tarjetas_comercios_mov.codigo_comercio ASC
			   ";
		$datos = DB::select($sql);

	return $datos;
	}

}