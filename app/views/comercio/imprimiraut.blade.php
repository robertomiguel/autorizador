<?php $total = 0; ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
  .table {
    width: 100%;
  }
  .table tr:nth-child(even) {background: #CCC}
  .table tr:nth-child(odd) {background: #FFF}

   .letra8 {font-size: 8px}
   .letra9 {font-size: 9px}
  .letra10 {font-size: 10px}
  .letra11 {font-size: 11px}
  .letra12 {font-size: 12px}
  .letra13 {font-size: 13px}
  .letra14 {font-size: 14px}
  .letra15 {font-size: 15px}

.footer {
    width: 100%;
    position: fixed;
    bottom: 0px;
    border-collapse: collapse;
    padding: 0;
}
.tablapie{
  width: 100%;

}
.derecha{
  float: right;
}

.izquierda{
  float: left;
}

</style>
<table>
  <tr>
    <td>
      <img src="{{ public_path()}}/img/goya/Logotipo.bmp" alt="">
    </td>
    <td>
      <span class="letra14">{{Empresa::find(1)->nombre_int}}</span> <br>
      <span class="letra13">Autorizaciones de Tarjeta del Día</span> <br>
      <span class="letra12">{{Formatos::fecha(Formatos::fechaActual())}}</span>
    </td>
  </tr>
</table>


<table class="table letra11">
  <thead>
    <tr>
      <tD>Hora</tD>
      <tD>Nombre</tD>
      <tD>Tarjeta</tD>
      <tD>Importe</tD>
      <tD>Cupón</tD>
      <tD>Cod. Aut.</tD>
      <tD>Cuotas</tD>
    </tr>
  </thead>
  <tbody>
      @foreach ($operaciones as $op)
        <tr>
          <td>{{$op->hora}}</td>
          <td>{{Formatos::capital($op->nombre)}}</td>
          <td>{{Formatos::tarjetaguion($op->numero_tarjeta)}}</td>
          <td>{{Formatos::moneda($op->importe)}}</td>
          <td>{{$op->numero_cupon}}</td>
          <td>{{$op->codigo_autorizacion.'/'.$op->codigo_autorizacion_add}}</td>
          <td>{{$op->cuotas}}</td>
        </tr>
        <?php $total = $total + $op->importe; ?>
      @endforeach
  </tbody>
  <tfoot>
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td>Total</td>
      <td>$ {{$total}}</td>
    </tr>
  </tfoot>  
</table>

<div class="footer">
  <hr>
  <table class="tablapie">
    <tr>
      <td align="left">Página</td>
      <td align="center"></td>
      <td align="right">Neo Autorizador</td>
    </tr>
  </table>
</div>

<script type="text/php">
  if ( isset($pdf) ) {
            $font = Font_Metrics::get_font("helvetica");
            $pdf->page_text(72, 824, "{PAGE_NUM} de {PAGE_COUNT}", $font, 11, array(255,0,0));
      }
</script>