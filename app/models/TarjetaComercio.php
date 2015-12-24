<?php

class TarjetaComercio extends Eloquent {

	protected $table = 'tarjetas_comercios';
	protected $primaryKey = 'codigo_comercio';
	public $timestamps = false;

	static public function retenciones($importe){
		$nro_comercio = Persona::numeroComercio();
		$adec_porc_tomar_pago = 100;
		$ldec_imponible = $importe;
		$ret_iva = 0;
		$ret_gan = 0;
		$ret_iibb = 0;
		$li_condicion_iva 		= 0;
		$li_rubro_comercio 		= 0;
		$li_retiene_iva 		= 0;
		$li_retiene_ganancias 	= 0;
		$li_retiene_ingbrutos 	= 0;
		$li_retiene_debcred 	= 0;
		$ll_codigo_provincia 	= 0;
		$li_presento_ddjj 		= 0;
		$li_convenio_multi 		= 0;

	$sql = "SELECT ISNULL( tarjetas_comercios.condicion_iva, 7 ) 	 AS li_condicion_iva, 
		 		   tarjetas_comercios.codigo_rubro_ret 				 AS li_rubro_comercio,   
		 		   ISNULL( tarjetas_comercios.retiene_iva, 0 ) 		 AS li_retiene_iva,   
		 		   ISNULL( tarjetas_comercios.retiene_ganancias, 0 ) AS li_retiene_ganancias,   
		 		   ISNULL( tarjetas_comercios.retiene_ingbrutos, 0 ) AS li_retiene_ingbrutos,   
		 		   ISNULL( tarjetas_comercios.retiene_debcred, 0 )   AS li_retiene_debcred,
	    		   localidad.codigo_provincia 						 AS ll_codigo_provincia,  
		 		   ISNULL( tarjetas_comercios.presento_ddjj, 0 ) 	 AS li_presento_ddjj,	
		 		   ISNULL( tarjetas_comercios.convenio_multilateral, 0 ) AS li_convenio_multi
			  FROM tarjetas_comercios 
		 LEFT JOIN Localidad ON
		 		   ( tarjetas_comercios.codigo_postal = localidad.codigo_postal )
			 WHERE tarjetas_comercios.nro_empresa 				    = 1 
			   AND tarjetas_comercios.nro_sucursal 				    = 1 
	  		   AND IsNull( tarjetas_comercios.comercio_propio, 0 )  = 0  
			   AND codigo_comercio 									= $nro_comercio
 		 ";
	$datos = DB::select($sql);

$li_condicion_iva 		= $datos[0]->li_condicion_iva;
$li_rubro_comercio 		= $datos[0]->li_rubro_comercio;
$li_retiene_iva 		= $datos[0]->li_retiene_iva;
$li_retiene_ganancias 	= $datos[0]->li_retiene_ganancias;
$li_retiene_ingbrutos 	= $datos[0]->li_retiene_ingbrutos;
$li_retiene_debcred 	= $datos[0]->li_retiene_debcred;
$ll_codigo_provincia 	= $datos[0]->ll_codigo_provincia;
$li_presento_ddjj 		= $datos[0]->li_presento_ddjj;
$li_convenio_multi 		= $datos[0]->li_convenio_multi;

if ($li_retiene_iva == 1) {
	$sql = "SELECT IsNull( tarjetas_ret_config.porcentaje, 0 ) as adec_porcentaje,   
				   IsNull( tarjetas_ret_config.no_imponible, 0 ) as adec_noimponible,   
				   IsNull( tarjetas_ret_config.exento, 0 ) as adec_exento,
				   IsNull( tarjetas_ret_config.depende_tabla, 0 ) as li_depende_tabla
			  FROM tarjetas_ret_config
			 WHERE ( tarjetas_ret_config.tipo_impuesto = 1 )
			   AND ( tarjetas_ret_config.codigo_rubro = $li_rubro_comercio )
			   AND ( tarjetas_ret_config.condicion_iva = $li_condicion_iva );
			";
	$datos = DB::select($sql);
	$iva_porcentaje 	= $datos[0]->adec_porcentaje;
	$iva_noimponible 	= $datos[0]->adec_noimponible;
	$iva_exento 		= $datos[0]->adec_exento;
	$iva_depende_tabla 	= $datos[0]->li_depende_tabla;

	if ($iva_noimponible >= $ldec_imponible) {
		$ret_iva = 0;
	} else {
		$ret_iva = round( ($ldec_imponible * $iva_porcentaje) / 100 ,2);
	}
	if ( $ret_iva <= $iva_exento) {
		$ret_iva = 0;
	}
}

if ($li_retiene_ganancias == 1) {
	$sql = "SELECT IsNull( tarjetas_ret_config.porcentaje, 0 )    AS adec_porcentaje,   
				   IsNull( tarjetas_ret_config.no_imponible, 0 )  AS adec_noimponible,   
				   IsNull( tarjetas_ret_config.exento, 0 ) 		  AS adec_exento,
				   IsNull( tarjetas_ret_config.depende_tabla, 0 ) AS li_depende_tabla
			 FROM tarjetas_ret_config
			WHERE ( tarjetas_ret_config.tipo_impuesto = 2 )
			  AND ( tarjetas_ret_config.codigo_rubro  = $li_rubro_comercio )
			  AND ( tarjetas_ret_config.condicion_iva = $li_condicion_iva )
	";
	$datos = DB::select($sql);
	$gan_porcentaje 	= $datos[0]->adec_porcentaje;
	$gan_noimponible 	= $datos[0]->adec_noimponible;
	$gan_exento 		= $datos[0]->adec_exento;
	$gan_depende_tabla 	= $datos[0]->li_depende_tabla;

	if ($gan_noimponible >= $ldec_imponible) {
		$ret_gan = 0;
	} else {
		$ret_gan = round( ($ldec_imponible * $gan_porcentaje) / 100 ,2);
	}
	if ( $ret_gan <= $gan_exento) {
		$ret_gan = 0;
	}
}

if ($li_retiene_ingbrutos == 1) {
	$sql = "SELECT IsNull( tarjetas_ret_config.porcentaje, 0 ) as adec_porcentaje,   
				   IsNull( tarjetas_ret_config.no_imponible, 0 ) as adec_noimponible,   
				   IsNull( tarjetas_ret_config.exento, 0 ) as adec_exento,
				   IsNull( tarjetas_ret_config.depende_tabla, 0 ) as li_depende_tabla
			  FROM tarjetas_ret_config
			 WHERE ( tarjetas_ret_config.tipo_impuesto = 3 )
			   AND ( tarjetas_ret_config.codigo_rubro = $li_rubro_comercio )
			   AND ( tarjetas_ret_config.condicion_iva = $li_condicion_iva )
		   ";
	$datos = DB::select($sql);
	$iibb_porcentaje 	= $datos[0]->adec_porcentaje;
	$iibb_noimponible 	= $datos[0]->adec_noimponible;
	$iibb_exento 		= $datos[0]->adec_exento;
	$iibb_depende_tabla = $datos[0]->li_depende_tabla;

	if ($iibb_noimponible >= $ldec_imponible) {
		$ret_iibb = 0;
	} else {
		$ret_iibb = round( ($ldec_imponible * $iibb_porcentaje) / 100 ,2);
	}
	if ( $ret_iibb <= $iibb_exento) {
		$ret_iibb = 0;
	}
}
$total = $ret_iva + $ret_gan + $ret_iibb;

$retenciones = array();
$retenciones[1] = $ret_iva;
$retenciones[2] = $ret_gan;
$retenciones[3] = $ret_iibb;
$retenciones[4] = $total;

return $retenciones;

	}
}