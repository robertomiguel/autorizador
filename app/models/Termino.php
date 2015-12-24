<?php

class Termino extends Eloquent {

	protected $table = 'deposito_termino';
	protected $primaryKey = 'numero_certificado';
	public $timestamps = false;
	
	static public function activos(){

		$nro_socio = Persona::numeroSocio();

		$sql =   "SELECT numero_certificado,
						 fecha_deposito,
						 fecha_vencimiento,
						 capital_certificado,
						 interes_certificado
 					FROM deposito_termino
 				   WHERE estado_certificado = 1
 					 AND numero_orden 		= 0
					 AND numero_socio 		= $nro_socio";
	
	$datos = DB::select($sql);
	
	return $datos;
	}
}
