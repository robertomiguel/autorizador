<?php 
class UsuarioControlador extends BaseController {

    public function getIndex()
    {
		if (Auth::user()->nivel == 1 or Auth::user()->nivel == 3) { // socio y socio-comercio
        $nombre       = Persona::nombreCompleto();
		$nrosocio     = 'Socio: '.Persona::numeroSocio();
		$cuentas      = cuentasAhorro::socioCuentas();
        $prestamos    = Prestamos::activos();
        $transitorias = Transitoria::activos();
        $cheques      = Cheques::rechazados();
        $terminos     = Termino::activos();
	 }
       if (Auth::user()->nivel == 3 or Auth::user()->nivel == 2) { // comercio-socio - comercio
        $liqcomercio = TarjetaComercioMov::liqcomercio();
           } else {$liqcomercio = 'no';}
       
       if (Persona::usaTarjeta()){
         $limites = Socio::limites(Persona::numeroSocio());
         $liqusuario = TarjetaUsuarioMov::liqusuario();
            if (count($liqusuario)>0){
            foreach ($liqusuario as $liq) {
                $nro_liquidacion = $liq->nro_liquidacion;
            }
         $liqconsumos = TarjetasConsumos::consumos($nro_liquidacion);
        } else {$liqusuario = 'noliq'; $liqconsumos = 'no';}
       } else { $liqusuario = 'no'; $liqconsumos = 'no'; $limites= ''; }
       
       if (Auth::user()->nivel == 2) { // comercio
        $nombre       = Persona::nombreComercio();
        $nrosocio     = 'Comercio: '.Persona::numeroComercio();
        $cuentas      = 'no';
        $prestamos    = 'no';
        $transitorias = 'no';
        $cheques      = 'no';
        $terminos     = 'no';
        $liqconsumos  = 'no';
       }
            if (Formatos::esCelular()){
        return View::make('usuario.celular.inicio')
                    ->with('nombrecompleto',$nombre)
                    ->with('nrosocio',$nrosocio)
                    ->with('cuentas',$cuentas)
                    ->with('prestamos',$prestamos)
                    ->with('transitorias',$transitorias)
                    ->with('cheques',$cheques)
                    ->with('terminos',$terminos)
                    ->with('liqcomercio',$liqcomercio)
                    ->with('liqusuario',$liqusuario)
                    ->with('liqconsumos',$liqconsumos)
                    ->with('limites',$limites);

        } else {
        return View::make('usuario.inicio')
                    ->with('nombrecompleto',$nombre)
                    ->with('nrosocio',$nrosocio)
                    ->with('cuentas',$cuentas)
                    ->with('prestamos',$prestamos)
                    ->with('transitorias',$transitorias)
                    ->with('cheques',$cheques)
                    ->with('terminos',$terminos)
                    ->with('liqcomercio',$liqcomercio)
                    ->with('liqusuario',$liqusuario)
                    ->with('liqconsumos',$liqconsumos)
                    ->with('limites',$limites);
        }
    }
    public function anyCuentaDetalle($cuenta) {
        $detalle = MovimientosAhorro::detalleCuenta($cuenta);
        
        $ini = parse_ini_file("../app/config/tipo_cbte.ini");
        $saldo = 0;
        $indice = 0;
        $tabla = ' ';

        foreach ($detalle as $mov) {
            $indice++;
            if ( ($mov->tipo_cbte % 2) == 0 ){                            
                $credito = $mov->monto;
                $debito = '';
                $saldo = $saldo + ($mov->monto * 1);
            } else {
                $debito = $mov->monto;
                $credito = '';
                $saldo = $saldo + ($mov->monto * -1);
            }
            $cbte = $ini[$mov->tipo_cbte];
            $tabla = $tabla . "<tr>
            <td>$indice</td>
            <td  align='center'>".Formatos::fecha($mov->fecha_acreditacion)."</td>
            <td>$cbte</td>
            <td align='center'>($mov->clase_cbte) $mov->numero_cbte</td>
            <td align='right'>".Formatos::moneda($debito)."</td>
            <td align='right'>".Formatos::moneda($credito)."</td>
            <td align='right'>".Formatos::moneda($saldo)."</td>
            </tr>";
            
        }
        //$tabla = $tabla . "</table>";
        return $tabla;
    }

    public function anyPrestamoDetalle($socio){
        return;
    }
}