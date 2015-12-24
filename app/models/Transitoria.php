<?php

class Transitoria extends Eloquent {

	protected $table = 'aye_transitoria';
	protected $primaryKey = 'nro_aye_trans';
	public $timestamps = false;
	
	static public function activos(){
		$nro_socio = Persona::numeroSocio();
		$sql =   "SELECT aye_transitoria.nro_aye_trans,
         				 aye_transitoria.monto_limite,
		 				 aye_transitoria.fecha_vencimiento,
         				 aye_transitoria.monto_neto,   
         				 aye_transitoria.fecha_alta,   
						 destinos_aye.descripcion_corta,
						 servicios.nombre_servicio AS nombre_servicio
      				FROM aye_transitoria  
					JOIN servicios ON
						 ( aye_transitoria.codigo_servicio = servicios.codigo_servicio )
					JOIN estados_prestamo ON
						 ( aye_transitoria.nro_empresa  = estados_prestamo.nro_empresa and   
			        	   aye_transitoria.nro_sucursal = estados_prestamo.nro_sucursal and
					  	   aye_transitoria.estado       = estados_prestamo.codigo_estado_prestamo )   
					LEFT OUTER JOIN destinos_aye ON
						 			( aye_transitoria.nro_destino = destinos_aye.numero_destino )

				   WHERE aye_transitoria.numero_socio    	 = $nro_socio
				     AND IsNull(aye_transitoria.anulada,0) 	 = 0
				     AND IsNull(aye_transitoria.cancelada,0) = 0

					ORDER BY aye_transitoria.nro_aye_trans";

	$datos = DB::select($sql);

	return $datos;
	}
}