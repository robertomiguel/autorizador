<?php

class Prestamos extends Eloquent {

	protected $table = 'prestamos';
	protected $primaryKey = 'numero_prestamo';
	public $timestamps = false;
	
	static public function activos(){
		$nro_socio = Persona::numeroSocio();
/*		$sql =   "SELECT prestamos.numero_prestamo, 
						 plan_prestamos_2.nombre_plan, 
						 prestamos.fecha_emision_prestamo, 
						 prestamos.monto_liquido, 
						 prestamos.monto_total 
				   FROM prestamos,   
				        plan_prestamos_2 
				  WHERE prestamos.numero_plan = plan_prestamos_2.numero_plan 
					AND prestamos.prestamo_cancelado = 0
					AND prestamos.estado_prestamo <> 9
					AND prestamos.numero_socio = $nro_socio
			   ORDER BY prestamos.fecha_emision_prestamo ASC, 
						convert (dec(12,0), prestamos.numero_prestamo)";
*/
    $sql = "
        SELECT  prestamos.numero_prestamo, 
                plan_prestamos_2.nombre_plan, 
                prestamos.fecha_emision_prestamo, 
                prestamos.monto_total_final,

                prestamos.monto_total_final -
                SUM (
                  isNull(imputado_capital,0)     +
                  isNull(imputado_interes,0)     +
                  isNull(imputado_gtos_admin,0)  +
                  isNull(imputado_sellado_1,0)   +
                  isNull(imputado_sellado_2,0)   +
                  isNull(imputado_gasto_1,0)     +
                  isNull(imputado_gasto_2,0)     +
                  isNull(imputado_gasto_3,0)     +
                  isNull(imputado_gasto_4,0)     +
                  isNull(condonado_capital,0)    +
                  isNull(condonado_interes,0)    +
                  isNull(condonado_gtos_admin,0) +
                  isNull(condonado_sellado_1,0)  +
                  isNull(condonado_gasto_1,0)    +
                  isNull(condonado_gasto_2,0)    +
                  isNull(condonado_sellado_2,0)  +
                  isNull(condonado_gasto_3,0)    +
                  isNull(condonado_gasto_4,0)
                ) AS saldo

          FROM prestamos
          JOIN prestamos_vencimientos ON
               ( prestamos.numero_prestamo  = prestamos_vencimientos.numero_prestamo 
               AND  prestamos.numero_plan   = prestamos_vencimientos.numero_plan )
     LEFT JOIN prestamos_relacion ON
               ( prestamos_vencimientos.numero_prestamo                = prestamos_relacion.numero_prestamo
               AND  prestamos_vencimientos.numero_plan                 = prestamos_relacion.numero_plan
               AND  prestamos_vencimientos.numero_vencimiento_prestamo = prestamos_relacion.numero_vencimiento_prestamo
               AND  prestamos_relacion.tipo_cbte_pago                 <> 17 ), plan_prestamos_2

    WHERE prestamos.numero_plan                   = plan_prestamos_2.numero_plan 
      AND prestamos.prestamo_cancelado            = 0
      AND prestamos.estado_prestamo              <> 9
      AND prestamos.numero_socio                  = $nro_socio
      AND IsNull(plan_prestamos_2.solo_tarjeta,0) = 0

    GROUP BY
                prestamos.numero_prestamo, 
                prestamos.numero_plan,
                plan_prestamos_2.nombre_plan, 
                prestamos.fecha_emision_prestamo, 
                prestamos.monto_liquido, 
                prestamos.monto_total,
                prestamos.monto_total_final,
                cuotas_prestamo,
                prestamos.monto_cuota

    ORDER BY prestamos.fecha_emision_prestamo DESC, 
             prestamos.numero_plan,
             CONVERT (DEC(12,0), prestamos.numero_prestamo)

    ";
	$datos = DB::select($sql);
	
	return $datos;
	}
}