<?php

class TarjetasPlanes extends Eloquent {

	protected $table = 'tarjetas_planes';
	protected $primaryKey = 'codigo_plan';
	public $timestamps = false;

	public static function validarCuotas($cant_cuotas) {
		$sql = "
		 SELECT count(tarjetas_planes.codigo_plan) as planes
  		   FROM tarjetas_planes  
   		  WHERE tarjetas_planes.nro_empresa = 1
			AND tarjetas_planes.nro_sucursal = 1
			AND IsNull( tarjetas_planes.plan_tickets, 0 ) = 0 
			AND IsNull( tarjetas_planes.plan_tickets_tel, 0 ) = 0 
			AND tarjetas_planes.comercio_usuario >= 2
			AND IsNull(tarjetas_planes.habilitado, 0) = 1
			AND tarjetas_planes.cantidad_cuotas = $cant_cuotas
		";

		$datos = DB::select($sql);
		return $datos[0]->planes;
	}

	public static function nroPlan($cant_cuotas) {

		$sql = "
		 SELECT tarjetas_planes.codigo_plan as nroPlan
  		   FROM tarjetas_planes  
   		  WHERE tarjetas_planes.nro_empresa = 1
			AND tarjetas_planes.nro_sucursal = 1
			AND IsNull( tarjetas_planes.plan_tickets, 0 ) = 0 
			AND IsNull( tarjetas_planes.plan_tickets_tel, 0 ) = 0 
			AND tarjetas_planes.comercio_usuario >= 2
			AND IsNull(tarjetas_planes.habilitado, 0) = 1
			AND tarjetas_planes.cantidad_cuotas = $cant_cuotas
		";

		$datos = DB::select($sql);
		return $datos[0]->nroPlan;
	}

}