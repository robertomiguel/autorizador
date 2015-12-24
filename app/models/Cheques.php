<?php

class Cheques extends Eloquent {

	protected $table = 'cheques';
	protected $primaryKey = 'numero_cheque';
	public $timestamps = false;

		static public function rechazados(){
		$nro_socio = Persona::numeroSocio();
		$sql =    "SELECT
						  cheques.numero_cheque,
						  cheques.fecha_cheque,
						  cheques.fecha_acreditacion,
						  cheques.monto_cheque,
						  bancos.nombre_banco
					 FROM cheques
					 JOIN bancos ON 
					      bancos.codigo_banco = cheques.codigo_banco
					WHERE codigo_ingreso = $nro_socio
 					 AND estado_cheque	= 1
 					 ORDER BY 2 DESC";

	$datos = DB::select($sql);

	return $datos;
	}
}