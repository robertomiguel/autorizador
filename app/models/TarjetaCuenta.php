<?php
class TarjetaCuenta extends Eloquent {

	protected $table = 'tarjetas_cuentas';
	protected $primaryKey = 'numero_socio';
	public $timestamps = false;

	static public function numeroCuenta() {
		$nro_socio = Persona::numeroSocio();
		$numero_cuenta = TarjetaCuenta::find($nro_socio)->numero_cuenta;

		return $numero_cuenta;
	}

	public static function saldos($nro_socio, $fecha_hoy) {

	$sql = "
		SELECT tarjetas_cuentas.limite_credito -  	
						IsNull(									
						(Select sum(	Case 	tarjetas_usuarios_mov.tipo_cbte_liquidacion 		     
												When  31 Then  tarjetas_usuarios_mov.saldo_actual_con	  
														Else	( tarjetas_usuarios_mov.importe_cobro * -1 )	
											End																			
									) 																					   
							 From tarjetas_usuarios_mov 															
							Where tarjetas_usuarios_mov.nro_empresa    =  1
							  and tarjetas_usuarios_mov.nro_sucursal   =	 1
							  and	tarjetas_usuarios_mov.codigo_tarjeta =	1
							  and tarjetas_usuarios_mov.numero_cuenta = tarjetas_cuentas.numero_cuenta	  
							  and IsNull( tarjetas_usuarios_mov.anulado, 0) = 0 
							  and tarjetas_usuarios_mov.codigo_liquidacion = (Select Max(codigo_liquidacion) 	 
																							From tarjetas_liquidaciones 			  
																						  Where tarjetas_liquidaciones.nro_empresa    =  1
																							 And tarjetas_liquidaciones.nro_sucursal   =  1
																							 And tarjetas_liquidaciones.codigo_tarjeta = 1 
																							 And tarjetas_liquidaciones.numero_ciclo = tarjetas_cuentas.codigo_ciclo	 
																							 And tarjetas_liquidaciones.cerrado = 1) ), 0 )	    -
					IsNull( 																																   
						(Select sum(	Case IsNull( tarjetas_consumos.codigo_ajuste, 0 ) 												    	
											 When 0 Then	tarjetas_consumos.importe_compra 												    	
												Else																										    	
													Case 	tarjetas_codigos_ajustes.tipo_ajuste												    	
														When  5 Then  tarjetas_consumos.importe_compra										    	
														Else	( tarjetas_consumos.importe_compra * -1 )										    	
														End																								    	
											  End ) 																										 	 	
							 From tarjetas_codigos_ajustes right outer join tarjetas_consumos 												
										on ( tarjetas_codigos_ajustes.nro_empresa   = tarjetas_consumos.nro_empresa  and 				
											  tarjetas_codigos_ajustes.nro_sucursal  = tarjetas_consumos.nro_sucursal and				 	
											  tarjetas_codigos_ajustes.codigo_ajuste = tarjetas_consumos.codigo_ajuste 	)				
							where tarjetas_consumos.codigo_liquidacion = (Select min(codigo_liquidacion) 							    	
																							From tarjetas_liquidaciones 							    	
																						  Where tarjetas_liquidaciones.nro_empresa    = 1
																							 And tarjetas_liquidaciones.nro_sucursal   = 1
																							 And tarjetas_liquidaciones.codigo_tarjeta = 1
																							 And tarjetas_liquidaciones.numero_ciclo = tarjetas_cuentas.codigo_ciclo	
																							 And tarjetas_liquidaciones.cerrado = 0) 			  
							  And IsNull( tarjetas_consumos.compra_anulada, 0) = 0															
							  And tarjetas_consumos.tipo_consumo not in ( 2,3,7,8,13,14) 											     
							  And tarjetas_consumos.comercio_usuario >= 2 																	  
							  And IsNull(tarjetas_consumos.liquidada , 0 ) = 0 																
							  And tarjetas_consumos.numero_cuenta = tarjetas_cuentas.numero_cuenta ) ,0 ) As SALDO, 			   
						IsNull( (Select Max( saldo_mutuo ) 																						   
										From mutuos 		  																							   
									  Where mutuos.nro_empresa  = 1
										 And mutuos.nro_sucursal = 1
										 And mutuos.numero_socio = tarjetas_cuentas.numero_socio 											    
										 And mutuos.fecha_alta <= '$fecha_hoy'
										 And mutuos.fecha_vencimiento > '$fecha_hoy'
										 And IsNull( mutuos.anulado, 0) = 0) ,0 ) As Saldo_cuotas,
						tarjetas_cuentas.limite_compra_cuotas
			  FROM personas,
					 tarjetas_cuentas																	
			 WHERE tarjetas_cuentas.nro_empresa  = 1
				And tarjetas_cuentas.nro_sucursal = 1
				And tarjetas_cuentas.numero_socio = personas.soc_numero_socio
				And tarjetas_cuentas.codigo_tarjeta	= 1
				And tarjetas_cuentas.numero_cuenta	= $nro_socio
		";

		$datos = DB::select($sql);
		return $datos;
	}

}