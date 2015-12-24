<?php

class TarjetasConsumos extends Eloquent {

	protected $table = 'tarjetas_consumos';
	protected $primaryKey = 'nro_liquidacion';
	public $timestamps = false;

	static public function consumos(){
		$numero_cuenta = TarjetaCuenta::numeroCuenta();
		$sql =  "SELECT MAX(tum.nro_liquidacion) AS ultima_liq
		           FROM tarjetas_usuarios_mov tum
            	  WHERE tum.nro_empresa     		= 1
		            AND tum.nro_sucursal    		= 1
					AND tum.codigo_tarjeta  		= 1
					AND tum.numero_cuenta   	  	= $numero_cuenta
					AND tum.tipo_cbte_liquidacion 	= 31
				";

		$datos = DB::select($sql);
		
		$nro_liquidacion = $datos[0]->ultima_liq;
		
		$sql = "SELECT fecha_consumo,
					   personas.com_nombre_fantasia,
					   cuota_nro,
					   cuota_total,
					   importe_compra
				  FROM tarjetas_consumos
				  JOIN personas	ON
				  	   ( personas.com_numero_comercio = tarjetas_consumos.codigo_comercio )
				 WHERE nro_liquidacion = '$nro_liquidacion'
				   AND compra_anulada  = 0
				 ORDER BY fecha_consumo ASC
		        ";

	$datos = DB::select($sql);
	return $datos;
	}
}