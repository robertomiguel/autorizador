<?php

class TarjetaUsuarioMov extends Eloquent {

	protected $table = 'tarjetas_usuarios_mov';
	protected $primaryKey = 'nro_liquidacion';
	public $timestamps = false;

	static public function liqusuario() {
		$numero_cuenta = TarjetaCuenta::numeroCuenta();

		$sql = "
	   SELECT 
    		  tarjetas_usuarios_mov.nro_liquidacion,
    		  tarjetas_usuarios_mov.fecha_liquidacion,
    		  tarjetas_usuarios_mov.fecha_vto_pmin_actual,
    		  tarjetas_usuarios_mov.saldo_actual_con,
    		  pagos.fecha_cobro,
    		  pagos.importe_cobro
	  	 FROM tarjetas_usuarios_mov
	LEFT JOIN tarjetas_usuarios_mov pagos ON
      		  tarjetas_usuarios_mov.nro_empresa     = pagos.nro_empresa
		  AND tarjetas_usuarios_mov.nro_sucursal    = pagos.nro_sucursal
		  AND tarjetas_usuarios_mov.codigo_tarjeta  = pagos.codigo_tarjeta
		  AND tarjetas_usuarios_mov.numero_cuenta   = pagos.numero_cuenta
		  AND tarjetas_usuarios_mov.nro_liquidacion = pagos.nro_liquidacion
		  AND pagos.tipo_cbte_liquidacion 			= 32
	WHERE tarjetas_usuarios_mov.nro_empresa     = 1
  	  AND tarjetas_usuarios_mov.nro_sucursal    = 1
  	  AND tarjetas_usuarios_mov.codigo_tarjeta  = 1
  	  AND tarjetas_usuarios_mov.numero_cuenta   = $numero_cuenta
  	  AND tarjetas_usuarios_mov.nro_liquidacion = (SELECT MAX(tum.nro_liquidacion)
  	  												 FROM tarjetas_usuarios_mov tum
                                                    WHERE tum.nro_empresa     = tarjetas_usuarios_mov.nro_empresa
                                                      AND tum.nro_sucursal    = tarjetas_usuarios_mov.nro_sucursal
                                                      AND tum.codigo_tarjeta  = tarjetas_usuarios_mov.codigo_tarjeta
                                                      AND tum.numero_cuenta   = tarjetas_usuarios_mov.numero_cuenta
                                                      AND tum.tipo_cbte_liquidacion = 31 )
	  AND tarjetas_usuarios_mov.tipo_cbte_liquidacion = 31
	";
		$datos = DB::select($sql);
		return $datos;
	}

	static public function detalleConsumo() {
			$numero_cuenta = TarjetaCuenta::numeroCuenta();
		$sql = "
				SELECT MAX(tum.nro_liquidacion) AS ultima_liq
				  FROM tarjetas_usuarios_mov tum
		         WHERE tum.nro_empresa   	= 1
		           AND tum.nro_sucursal    	= 1
				   AND tum.codigo_tarjeta  	= 1
				   AND tum.numero_cuenta   	= $numero_cuenta
				   AND tum.tipo_cbte_liquidacion = 31
		";

		$datos = DB::select($sql);
		
		$nro_liquidacion = $datos[0]->ultima_liq;
// detalle
$sql = "
		SELECT (select sum(importe_cobro) * -1 AS importe_cobro
		  FROM tarjetas_usuarios_mov
		 WHERE tarjetas_usuarios_mov.nro_liquidacion = ( SELECT MAX( nro_liquidacion )
														   FROM tarjetas_usuarios_mov
													      WHERE nro_empresa 	= 1
														    AND nro_sucursal 	= 1
														    AND codigo_tarjeta 	= 1
														    AND numero_cuenta 	= $numero_cuenta
														    AND nro_liquidacion < '$nro_liquidacion'
														    AND tipo_cbte_liquidacion = 31 )
		   AND  tipo_cbte_liquidacion = 32) * -1 AS pago_anterior,
		  		isNull(tarjetas_usuarios_mov.interes_fin_peso,0) AS interes,
		        tarjetas_usuarios_mov.numero_cuenta,
		        tarjetas_plasticos.numero_tarjeta,
		        personas.apellido AS personas_apellido,
		        personas.nombre	as personas_nombre,
				tarjetas_liquidaciones.fecha_cierre,
				tarjetas_liquidaciones.fecha_vto,
				tarjetas_liquidaciones.fecha_cierre_prox,
				tarjetas_liquidaciones.fecha_vto_prox,
					
				isNull(tarjetas_cuentas.limite_compra_contado,0) AS contado,
			 	isNull(tarjetas_cuentas.limite_compra_cuotas,0) AS cuotas,
				isNull(tarjetas_cuentas.limite_adelanta_efectivo,0) AS efectivo,

				tarjetas_usuarios_mov.nro_liquidacion,
				tarjetas_usuarios_mov.saldo_actual_con,
				tarjetas_usuarios_mov.pmin_ant_imp,
				tarjetas_usuarios_mov.pmin_actual,

				tarjetas_usuarios_mov.fecha_liquidacion,

				tarjetas_liquidaciones.fecha_cierre_ant,
				tarjetas_liquidaciones.fecha_vto_ant,
				tarjetas_usuarios_mov.sellados as sellado_consumo,
				tarjetas_usuarios_mov.cargos_resumen,
				tarjetas_usuarios_mov.seguro_vida,
				(SELECT SUM(importe_compra) AS sellado_prestamo
				   FROM tarjetas_consumos
				  WHERE tipo_consumo IN ( 5 , 15 )
				    AND numero_cuenta 		= tarjetas_usuarios_mov.numero_cuenta
				    AND codigo_liquidacion 	= tarjetas_liquidaciones.codigo_liquidacion
				 ) AS sellado_prestamo

    FROM tarjetas_cuentas,
         tarjetas_plasticos,
         personas,
			tarjetas_liquidaciones,
			parametros_tarjeta,
			tarjetas_usuarios_mov,
			empresa,
			localidad as localidad_empresa,
			localidad as localidad_usuario,
			provincia as provincia_empresa,
			provincia as provincia_usuario,
			tarjetas_nro_cabecera   
   WHERE tarjetas_nro_cabecera.nro_empresa 		= tarjetas_plasticos.nro_empresa 
		AND tarjetas_nro_cabecera.nro_sucursal 	= tarjetas_plasticos.nro_sucursal 
		AND tarjetas_nro_cabecera.codigo_tarjeta 	= tarjetas_plasticos.codigo_tarjeta
	  And tarjetas_plasticos.nro_empresa 			= tarjetas_usuarios_mov.nro_empresa 
	  AND tarjetas_plasticos.nro_sucursal 			= tarjetas_usuarios_mov.nro_sucursal 
	  AND tarjetas_plasticos.codigo_tarjeta 		= tarjetas_usuarios_mov.codigo_tarjeta 
	  AND tarjetas_plasticos.numero_cuenta 		= tarjetas_usuarios_mov.numero_cuenta 
	  AND tarjetas_cuentas.nro_empresa				= tarjetas_usuarios_mov.nro_empresa
	  AND tarjetas_cuentas.nro_sucursal				= tarjetas_usuarios_mov.nro_sucursal
	  AND tarjetas_cuentas.codigo_tarjeta			= tarjetas_usuarios_mov.codigo_tarjeta
	  AND tarjetas_cuentas.numero_cuenta			= tarjetas_usuarios_mov.numero_cuenta
	  AND tarjetas_liquidaciones.nro_empresa		= tarjetas_cuentas.nro_empresa
	  AND tarjetas_liquidaciones.nro_sucursal		= tarjetas_cuentas.nro_sucursal
	  AND tarjetas_liquidaciones.codigo_tarjeta	= tarjetas_cuentas.codigo_tarjeta
	  AND tarjetas_liquidaciones.numero_ciclo		= tarjetas_cuentas.codigo_ciclo
	  AND tarjetas_liquidaciones.fecha_vto 		= tarjetas_usuarios_mov.fecha_vto_pmin_actual 
	  AND personas.nro_persona			 				= tarjetas_plasticos.nro_persona
	  And tarjetas_plasticos.numero_version		= ( Select Max( numero_version )
																		From tarjetas_plasticos tp2
																	  where tp2.nro_empresa = 	tarjetas_usuarios_mov.nro_empresa	 		
																		 And tp2.nro_sucursal = tarjetas_usuarios_mov.nro_sucursal 
																		 And tp2.codigo_tarjeta = tarjetas_usuarios_mov.codigo_tarjeta 
																		 And tp2.numero_cuenta = tarjetas_usuarios_mov.numero_cuenta )	
	  AND tarjetas_plasticos.numero_nosocio			= 0 
	  AND tarjetas_usuarios_mov.tipo_cbte_liquidacion = 31 
	  AND parametros_tarjeta.numero_orden			= 1 
	  AND tarjetas_usuarios_mov.nro_empresa 		= 1 
	  AND tarjetas_usuarios_mov.nro_sucursal 		= 1 
	  AND tarjetas_usuarios_mov.codigo_tarjeta 		= 1 
	  AND tarjetas_usuarios_mov.nro_liquidacion 	= '$nro_liquidacion' 
	  AND empresa.nro_empresa						= 1
	  AND empresa.nro_sucursal						= 1 
	  AND personas.codigo_postal					= localidad_usuario.codigo_postal 
	  AND localidad_usuario.codigo_provincia		= provincia_usuario.codigo_provincia
	  ANd empresa.codigo_postal						= localidad_empresa.codigo_postal
	  AND localidad_empresa.codigo_provincia		= provincia_empresa.codigo_provincia
	";
	
	$datos = DB::select($sql);

	return $datos;

	}

	static public function detalleListado(){
			$numero_cuenta = TarjetaCuenta::numeroCuenta();
		$sql = "
		SELECT MAX(tum.nro_liquidacion) as ultima_liq
		 FROM tarjetas_usuarios_mov tum
            WHERE tum.nro_empresa   = 1
            AND tum.nro_sucursal    = 1
			AND tum.codigo_tarjeta  = 1
			AND tum.numero_cuenta   = $numero_cuenta
			AND tum.tipo_cbte_liquidacion = 31
		";

		$datos = DB::select($sql);
		
		$nro_liquidacion = $datos[0]->ultima_liq;

		$sql = "
				SELECT  (personas.apellido+' '+personas.nombre) AS nombre,
		         		tarjetas_consumos.fecha_consumo AS fecha,
		         		tarjetas_consumos.numero_tarjeta AS tarjeta,
		         		(CONVERT(VARCHAR,tarjetas_consumos.codigo_autorizacion)
		         		+'/'+
		         		CONVERT(VARCHAR,tarjetas_consumos.codigo_autorizacion_add)) AS autorizacion,
		         		(CONVERT(VARCHAR,tarjetas_consumos.cuota_nro)+'/'+
		         		CONVERT(VARCHAR,tarjetas_consumos.cuota_total) ) AS cuota,
						( CASE IsNull(tarjetas_consumos.codigo_ajuste,0)
						WHEN 0 THEN 
							CASE tarjetas_consumos.tipo_consumo
								WHEN 0 THEN
									CASE tarjetas_consumos.cuota_total
										WHEN 1 THEN		
											tarjetas_consumos.importe_compra
										ELSE
											tarjetas_consumos.importe_compra
									END
							WHEN 1 THEN
												tarjetas_consumos.importe_compra

								ELSE 
												tarjetas_consumos.importe_compra
								END 
						ELSE CASE tarjetas_codigos_ajustes.tipo_ajuste
							WHEN 5 THEN tarjetas_consumos.importe_compra
							ELSE tarjetas_consumos.importe_compra * (-1)
							END 
					END ) AS importe_compra,

		         ( CONVERT(VARCHAR,tarjetas_consumos.cuota_nro)
		         + '/' +
		         CONVERT( VARCHAR,tarjetas_consumos.cuota_total)) as cuota,

					( CASE IsNull(tarjetas_consumos.codigo_ajuste,0)
						WHEN 0 THEN tarjetas_consumos.importe_total
						ELSE CASE tarjetas_codigos_ajustes.tipo_ajuste
							WHEN 5 THEN tarjetas_consumos.importe_total
							ELSE tarjetas_consumos.importe_total * (-1)
							END 
					END ) AS importe_total,

		         ISNULL(tarjetas_consumos.numero_cupon,0) AS cbte,

					ISNULL( tarjetas_comercios.nombre_fantasia, '' ) AS nombre_fantasia,
					tarjetas_consumos.nro_prestamo_aye,
								UPPER(tarjetas_consumos.referencia) AS detalle

		    FROM tarjetas_consumos
					LEFT JOIN tarjetas_comercios ON
						 tarjetas_consumos.nro_empresa    = tarjetas_comercios.nro_empresa
					AND tarjetas_consumos.nro_sucursal    = tarjetas_comercios.nro_sucursal
					AND tarjetas_consumos.codigo_comercio = tarjetas_comercios.codigo_comercio

					LEFT JOIN tarjetas_codigos_ajustes ON
						 tarjetas_consumos.nro_empresa   = tarjetas_codigos_ajustes.nro_empresa
					AND tarjetas_consumos.nro_sucursal   = tarjetas_codigos_ajustes.nro_sucursal
					AND tarjetas_consumos.codigo_tarjeta = tarjetas_codigos_ajustes.codigo_tarjeta
					AND tarjetas_consumos.codigo_ajuste  = tarjetas_codigos_ajustes.codigo_ajuste,
					tarjetas_cuentas,
		         tarjetas_plasticos,
		         personas
					LEFT JOIN localidad as localidad_usuario ON 
					          ( personas.codigo_postal	= localidad_usuario.codigo_postal )
					LEFT JOIN provincia as provincia_usuario ON 
						      ( localidad_usuario.codigo_provincia = provincia_usuario.codigo_provincia),
					tarjetas_liquidaciones,
					parametros_tarjeta,
					tarjetas_usuarios_mov,
					empresa,
					localidad as localidad_empresa,
					provincia as provincia_empresa,
					tarjetas_nro_cabecera   
		    WHERE tarjetas_nro_cabecera.nro_empresa 		= tarjetas_plasticos.nro_empresa 
			  AND tarjetas_nro_cabecera.nro_sucursal 		= tarjetas_plasticos.nro_sucursal 
			  AND tarjetas_nro_cabecera.codigo_tarjeta 		= tarjetas_plasticos.codigo_tarjeta
			  And tarjetas_plasticos.nro_empresa 			= tarjetas_consumos.nro_empresa 
		      AND tarjetas_plasticos.nro_sucursal 			= tarjetas_consumos.nro_sucursal 
		      AND tarjetas_plasticos.codigo_tarjeta 		= tarjetas_consumos.codigo_tarjeta 
		      AND tarjetas_plasticos.numero_tarjeta 		= tarjetas_consumos.numero_tarjeta 
			  AND tarjetas_cuentas.nro_empresa				= tarjetas_consumos.nro_empresa
			  AND tarjetas_cuentas.nro_sucursal				= tarjetas_consumos.nro_sucursal
			  AND tarjetas_cuentas.codigo_tarjeta			= tarjetas_consumos.codigo_tarjeta
			  AND tarjetas_cuentas.numero_cuenta			= tarjetas_consumos.numero_cuenta
			  AND tarjetas_liquidaciones.nro_empresa		= tarjetas_cuentas.nro_empresa
			  AND tarjetas_liquidaciones.nro_sucursal		= tarjetas_cuentas.nro_sucursal
			  AND tarjetas_liquidaciones.codigo_tarjeta		= tarjetas_cuentas.codigo_tarjeta
			  AND tarjetas_liquidaciones.numero_ciclo		= tarjetas_cuentas.codigo_ciclo
			  AND tarjetas_liquidaciones.codigo_liquidacion = tarjetas_consumos.codigo_liquidacion 
		      AND personas.nro_persona			 			= tarjetas_plasticos.nro_persona 
			  AND tarjetas_consumos.nro_empresa				= tarjetas_usuarios_mov.nro_empresa
			  AND tarjetas_consumos.nro_sucursal			= tarjetas_usuarios_mov.nro_sucursal
			  AND tarjetas_consumos.codigo_tarjeta			= tarjetas_usuarios_mov.codigo_tarjeta
			  AND tarjetas_consumos.numero_cuenta			= tarjetas_usuarios_mov.numero_cuenta
			  AND tarjetas_consumos.liquidada				= 1
			  AND tarjetas_consumos.nro_liquidacion			= '$nro_liquidacion' 
			  AND tarjetas_usuarios_mov.tipo_cbte_liquidacion 	= 31 
			  AND parametros_tarjeta.numero_orden				= 1 
		      AND tarjetas_consumos.nro_empresa 				= 1 
		      AND tarjetas_consumos.nro_sucursal 				= 1 
		      AND tarjetas_consumos.codigo_tarjeta 				= 1 
		      AND tarjetas_usuarios_mov.nro_liquidacion 			= '$nro_liquidacion' 
		      AND IsNull( tarjetas_consumos.compra_anulada, 0 ) 	= 0 
			  AND IsNull( tarjetas_consumos.comercio_usuario, 3 )  >= 2 
			  AND empresa.nro_empresa						= 1
			  AND empresa.nro_sucursal						= 1 
			  ANd empresa.codigo_postal						= localidad_empresa.codigo_postal
			  AND localidad_empresa.codigo_provincia		= provincia_empresa.codigo_provincia
			  AND tarjetas_consumos.tipo_consumo NOT IN ( 2 , 3 , 7 , 8 , 13, 14, 5 , 15) 
			ORDER BY tarjeta, fecha ASC
		";
		$datos = DB::select($sql);
		return $datos;
	}

}

// consumos
/*

*/