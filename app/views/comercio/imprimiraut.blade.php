<?php $total = 0; ?>
<CENTER>
  <h1>Autorizaciones del Día</h1>
  <h2>{{Formatos::fechaActual()}}</h2>
</CENTER>
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
          <td>{{$op->nombre}}</td>
          <td>{{$op->numero_tarjeta}}</td>
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
      <td></td>
      <td>Total</td>
      <td>{{$total}}</td>
    </tr>
  </tfoot>  
</table>