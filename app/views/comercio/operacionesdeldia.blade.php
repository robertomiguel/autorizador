<style>
  .modal-dialog, .modal-content {
    height-height: 90%;
}

.modal-body {
    max-height: calc(100% - 120px);
    overflow-y: scroll;
}
.boton {
  height: 50px;  
  color: blue;
}
</style>

<div>
  <table class="operaciones letra11">
    <tbody>
    <?php $total = 0; ?>
      @foreach ($operaciones as $op)
        <tr>
          <td>
          <pre>
        Hora: {{$op->hora}}
      Nombre: {{$op->nombre}}
Nro. Tarjeta: {{$op->numero_tarjeta}}
     Importe: {{Formatos::moneda($op->importe)}}
  Nro. Cupon: {{$op->numero_cupon}}
   Cód. Aut.: {{$op->codigo_autorizacion.'/'.$op->codigo_autorizacion_add}}
      Cuotas: {{$op->cuotas}}
      Estado: {{$op->estado}}
          </pre>
          </td>
        </tr>
        <?php $total = $total + $op->importe; ?>
      @endforeach
    </tbody>
  </table>
</div>

<script>
  $('#mensajepie').html({{count($operaciones)}}+' operaciones / Total: $ '+{{$total}} + ' - <a class="boton" href="autorizaciones/imprimir-operaciones" target="_BLANK">Imprimir</a>');
</script>