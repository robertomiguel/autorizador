<?php

class TarjetasPlasticos extends Eloquent {

	protected $table = 'tarjetas_plasticos';
	protected $primaryKey = 'numero_tarjeta';
	public $timestamps = false;

	public static function validarNro($nro_tarjeta,$fechahoy){
		$sql = "SELECT COUNT(tarjetas_plasticos.numero_cuenta) AS cuenta
				  FROM tarjetas_plasticos
				  JOIN personas ON
				  	   ( personas.soc_numero_socio = tarjetas_plasticos.numero_socio )
				  JOIN tarjetas_cuentas ON
				  	   ( tarjetas_cuentas.numero_socio =  personas.soc_numero_socio )
				 WHERE tarjetas_plasticos.numero_tarjeta  = '$nro_tarjeta'
				   AND tarjetas_plasticos.estado_registro = 0
				   AND fecha_vencimiento 				  > '$fechahoy'
				   AND fecha_inicio 					 <= '$fechahoy'
				   AND personas.soc_estado 				  = 'A'
				   AND tarjetas_cuentas.estado 			  = 0
				   ";
		
		$cuenta = DB::select($sql);
		return $cuenta[0]->cuenta;
	}

	public static function validarTarjeta($nro_tarjeta){
		$fechahoy = Formatos::fechaHoraActual();
		//(isNull(personas.apellido,'')+ ' '+isNull(personas.nombre,'')) as nombre,
		$sql = "SELECT tarjetas_plasticos.estado_registro AS tarjeta_estado,
					   fecha_vencimiento,
					   fecha_inicio,
					   personas.soc_estado AS socio_estado,
					   tarjetas_cuentas.estado AS cuenta_estado,
					   tarjetas_plasticos.numero_cuenta AS numero_cuenta,
					   tarjetas_plasticos.nro_persona AS nro_persona

				  FROM tarjetas_plasticos
				  JOIN personas ON
					   ( personas.soc_numero_socio = tarjetas_plasticos.numero_socio )
				  JOIN tarjetas_cuentas ON
					   ( tarjetas_cuentas.numero_socio =  personas.soc_numero_socio )
				 WHERE tarjetas_plasticos.numero_tarjeta = '$nro_tarjeta'
				";
		$datos = DB::select($sql);
		return $datos;
	}

	public static function cuenta($numero_tarjeta){

		$sql = "SELECT numero_cuenta AS cuenta
				  FROM tarjetas_plasticos
				 WHERE numero_tarjeta = '$numero_tarjeta'";

		$datos = DB::select($sql);

		return $datos[0]->cuenta;
	}
}