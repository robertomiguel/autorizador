<?php

class MovimientosAhorro extends Eloquent {


	public static function detalleCuenta($numero_cuenta) {

//--- Comprobar que la cuenta sea del usuario logueado
	$cuentas = cuentasAhorro::socioCuentas();

	$check = 0;
	foreach ($cuentas as $cuenta) {
		if ($cuenta->numero_cuenta==$numero_cuenta) {$check=1;}
		}

		if ($check==0) {
		return 'error';
	}

    $sql="
        SELECT  movimientos_ahorro.tipo_cbte,   
                movimientos_ahorro.clase_cbte,   
                movimientos_ahorro.numero_cbte,   
                movimientos_ahorro.monto,   
                movimientos_ahorro.fecha_acreditacion
          FROM  movimientos_ahorro
         WHERE	movimientos_ahorro.nro_empresa 		= 1
           AND  movimientos_ahorro.nro_sucursal 	= 1
           AND  movimientos_ahorro.numero_cuenta 	= $numero_cuenta
         ORDER  BY movimientos_ahorro.fecha_acreditacion ASC
        ";

    $datos = DB::select($sql);

    return $datos;

	}

    public static function resumenCuentaPro($numero_cuenta) {

    $cuentas = cuentasAhorro::socioCuentas();

    $check = 0;
    foreach ($cuentas as $cuenta) {
        if ($cuenta->numero_cuenta==$numero_cuenta) {$check=1;}
        }

        if ($check==0) {
        return 'error';
    }

    $sql="
        SELECT 
        CASE IsNull(movimientos_ahorro.totalmente_acreditado,0)
        	WHEN 0 THEN movimientos_ahorro.fecha_servicio
        	WHEN 1 THEN movimientos_ahorro.ultima_acreditacion
        END fecha,
                 movimientos_ahorro.tipo_cbte									as tipo_cbte,   

        CASE movimientos_ahorro.horas_cbte
        	WHEN 0 THEN movimientos_ahorro.numero_cbte
        	ELSE movimientos_ahorro.numero_cbte + '/'+ CONVERT(varchar, movimientos_ahorro.horas_cbte)
        END AS cbte,

        case
        	WHEN (movimientos_ahorro.tipo_cbte % 2) <> 0 and movimientos_ahorro.monto > 0 then movimientos_ahorro.monto * (-1)
        	ELSE null
        END AS debito,

        CASE 
        	WHEN (movimientos_ahorro.tipo_cbte % 2 ) = 0 and movimientos_ahorro.monto > 0 then movimientos_ahorro.monto
        	ELSE null
        END AS credito,

        CASE 
        	WHEN (movimientos_ahorro.tipo_cbte % 2) = 0 and movimientos_ahorro.monto_sin_acreditar > 0 then movimientos_ahorro.monto_sin_acreditar
        	WHEN movimientos_ahorro.monto_sin_acreditar = 0 then null
        	ELSE movimientos_ahorro.monto_sin_acreditar * -1
        END AS monto_no_acred,

            movimientos_ahorro.referencia as referencia,
            ROW_NUMBER() OVER (ORDER BY movimientos_ahorro.fecha_servicio) AS id,

        CASE 
            WHEN (tipo_cbte % 2)= 0 then monto
            ELSE monto * (-1)
        END saldo

         FROM movimientos_ahorro 

        WHERE movimientos_ahorro.nro_empresa   = 1 and   
              movimientos_ahorro.nro_sucursal  = 1 and   
              movimientos_ahorro.numero_cuenta = $numero_cuenta
        ORDER BY 1 ASC
        ";

    $datos = DB::select($sql);

    return $datos;

    }
}