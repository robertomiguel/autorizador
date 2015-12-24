<?php

class HBAutorizaciones extends Eloquent {

	protected $table = 'hb_autorizaciones';
	protected $primaryKey = 'nro_persona';
	public $timestamps = false;

	public static function operacionesDelDia($nro_comercio, $fechahoy) {

		$sql = "SELECT  CONVERT(varchar(5),hb_autorizaciones.fecha_autorizacion, 108) AS hora,
					    (adherente.apellido + ' ' + adherente.nombre) AS nombre,
						hb_autorizaciones.numero_tarjeta,
						hb_autorizaciones.importe,
						hb_autorizaciones.numero_cupon,
						hb_autorizaciones.codigo_autorizacion,
						hb_autorizaciones.codigo_autorizacion_add,
						CASE hb_autorizaciones.cant_cuotas
							WHEN 1 THEN 'Contado'
							ELSE CONVERT(varchar,hb_autorizaciones.cant_cuotas)
						END AS 'cuotas',
						CASE hb_autorizaciones.anulado
							WHEN 1 THEN 'Anulado'
							ELSE 'Autorizado'
						END AS 'estado'
				  FROM  hb_autorizaciones
				  JOIN  tarjetas_plasticos ON
				  	    ( tarjetas_plasticos.numero_tarjeta = hb_autorizaciones.numero_tarjeta )
				  JOIN  personas ON
				  	    ( personas.soc_numero_socio = tarjetas_plasticos.numero_socio )
				  JOIN  personas AS adherente ON
					    ( adherente.nro_persona = tarjetas_plasticos.nro_persona )
				 WHERE  hb_autorizaciones.codigo_comercio 	  = $nro_comercio
				   AND  hb_autorizaciones.fecha_autorizacion >= '$fechahoy'
				";

		$datos = DB::select($sql);

		return $datos;

	}
}
