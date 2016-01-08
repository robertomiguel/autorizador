<?php

class TarjetaPlanPromocion extends Eloquent {

	protected $table = 'tarjetas_planes_promocion';
	protected $primaryKey = 'cod_promocion';
	public $timestamps = false;

	public static function buscarPromo($codigo_plan) {

		$fecha_compra = Formatos::fechaHoraActual();
		$codigo_comercio = Persona::numeroComercio();
		$dia_semana = jddayofweek(cal_to_jd(CAL_GREGORIAN, date("m"),date("d"), date("Y")));
		$empresa 	= 1;
		$sucursal 	= 1;

		$sql = "
				SELECT TOP 1
					 tarjetas_planes_promocion.nombre_plan as nombre,
					 tarjetas_planes_promocion.vigencia_hasta as vencimiento
				  FROM tarjetas_planes_promocion
				 WHERE nro_empresa  = $empresa
					AND nro_sucursal = $sucursal
					AND tarjetas_planes_promocion.codigo_plan = $codigo_plan
					AND Isnull( comercios_todos, 0 )  = 1
					AND (( IsNull( domingo	, 0 ) 	= 1 AND 1 = $dia_semana ) or 
						  ( IsNull( lunes	, 0 ) 	= 1 AND 2 = $dia_semana ) or 
						  ( IsNull( martes	, 0 ) 	= 1 AND 3 = $dia_semana ) or 
						  ( IsNull( miercoles, 0 ) 	= 1 AND 4 = $dia_semana ) or 
						  ( IsNull( jueves	, 0 ) 	= 1 AND 5 = $dia_semana ) or 
						  ( IsNull( viernes	, 0 ) 	= 1 AND 6 = $dia_semana ) or 
						  ( IsNull( sabado	, 0 ) 	= 1 AND 7 = $dia_semana ) ) 
					AND vigencia_desde <= '$fecha_compra'
					AND vigencia_hasta >= '$fecha_compra'
					AND isnull(habilitado,0) = 1

				UNION ALL

				SELECT
					 tarjetas_planes_promocion.nombre_plan AS nombre,
					 tarjetas_planes_promocion.vigencia_hasta AS vencimiento

				  FROM tarjetas_planes_comercios
				  JOIN tarjetas_planes_promocion ON
						( tarjetas_planes_comercios.nro_empresa  	  =  tarjetas_planes_promocion.nro_empresa AND
						  tarjetas_planes_comercios.nro_sucursal 	  =  tarjetas_planes_promocion.nro_sucursal 	 AND
						  tarjetas_planes_comercios.codigo_plan  	  =  tarjetas_planes_promocion.codigo_plan  	 AND
						  tarjetas_planes_comercios.vigencia_desde  =  tarjetas_planes_promocion.vigencia_desde AND
						  tarjetas_planes_comercios.cod_promocion	  =  tarjetas_planes_promocion.cod_promocion	 )

				 WHERE tarjetas_planes_comercios.nro_empresa  		= $empresa
					AND tarjetas_planes_comercios.nro_sucursal 		= $sucursal
					AND tarjetas_planes_comercios.codigo_comercio 	= $codigo_comercio

					AND tarjetas_planes_comercios.codigo_plan 		= $codigo_plan
					AND (( IsNull( tarjetas_planes_promocion.domingo	, 0 ) = 1 AND 1 = $dia_semana ) or 
						  ( IsNull( tarjetas_planes_promocion.lunes		, 0 ) = 1 AND 2 = $dia_semana ) or 
						  ( IsNull( tarjetas_planes_promocion.martes	, 0 ) = 1 AND 3 = $dia_semana ) or 
						  ( IsNull( tarjetas_planes_promocion.miercoles	, 0 ) = 1 AND 4 = $dia_semana ) or 
						  ( IsNull( tarjetas_planes_promocion.jueves	, 0 ) = 1 AND 5 = $dia_semana ) or 
						  ( IsNull( tarjetas_planes_promocion.viernes	, 0 ) = 1 AND 6 = $dia_semana ) or 
						  ( IsNull( tarjetas_planes_promocion.sabado	, 0 ) = 1 AND 7 = $dia_semana ) ) 

					AND tarjetas_planes_comercios.vigencia_desde <= '$fecha_compra'
					AND tarjetas_planes_comercios.vigencia_hasta >= '$fecha_compra'
					AND isnull(tarjetas_planes_comercios.habilitado,0) = 1
				    AND isnull(tarjetas_planes_promocion.habilitado,0) = 1
		";

		$datos = DB::select($sql);

		if ( count($datos) <= 0 ) {
			return 'no hay';
		}

		return $datos;
	}

}