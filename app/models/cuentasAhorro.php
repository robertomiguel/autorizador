<?php

class CuentasAhorro extends Eloquent {

	protected $table = 'cuentas_ahorro';
	protected $primaryKey = 'numero_cuenta';
	public $timestamps = false;

	static public function socioCuentas(){
		$nro_socio = Persona::numeroSocio();
		$cuentas = DB::select("
						SELECT numero_cuenta
						  FROM cuentas_ahorro
						 WHERE numero_socio 	= $nro_socio
						   AND titular 			= 1
						   AND cuenta_anulada 	= 0
						 ORDER BY numero_cuenta ASC
		");
		return $cuentas;

	}


}