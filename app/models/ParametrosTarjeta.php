<?php

class ParametrosTarjeta extends Eloquent {

	protected $table = 'parametros_tarjeta';
	protected $primaryKey = 'ultimo_nro_autorizacion';
	public $timestamps = false;

	static public function codigoAutorizacion($pago){
    
  $sql = "
      Update parametros_tarjeta  
          Set ultimo_nro_autorizacion = IsNull( ultimo_nro_autorizacion, 0 ) + 1  
        Where parametros_tarjeta.numero_orden = 1 ";
  $actualizar = DB::update($sql);
  if  ($actualizar <> 1 ) {
    DB::rollback();
    return 'error';
  }

  //--- Tomar último nro de autorización 
  $sql = "
      Select parametros_tarjeta.ultimo_nro_autorizacion
          From parametros_tarjeta  
        Where parametros_tarjeta.numero_orden = 1 ";

  $ultimo_nro_autorizacion = DB::select($sql)[0]->ultimo_nro_autorizacion * 1;
  
  /*
    // Armar el código de autorizacion
    // an_consumo_ticket = 2
    // an_codigo_consumo = 0-contado 3-cuotas
  */
  //$consumo_ticket = 2;

  if($pago=='cuotas') {$pago = 3;}
  if($pago=='contado') {$pago = 0;}

  return $ultimo_nro_autorizacion.'/2'.$pago;

  }



}
