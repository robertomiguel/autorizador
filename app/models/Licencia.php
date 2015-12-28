<?php

class Licencia extends Eloquent {

	protected $table = 'clientes';
	protected $primaryKey = 'codigo_empresa';
	public $timestamps = false;

	public static function getLicencia ($empresa) {
		$sql = "
				SELECT clave
				  FROM clientes
				 WHERE nombre_empresa = '$empresa'
				   AND habilitado = 1
		";

		$licencia = DB::connection('licencias')->select($sql);

		return $licencia;
	}

}