<style>
.bajar {
  
  bottom: 2px; width: 100%;
}
.bajar button {

   float: right !important;   
}
</style>
<div class="modal fade" id="mensajecaja">
  <div class="modal-dialog">
    <div class="modal-content fondoventana">
      <div class="modal-header fondotitulo texto-sombra">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h5 class="modal-title" id="mensajetitulo">Notificación:</h5>
      </div>
      <div class="modal-body" id="mensajetexto" align="center">
        <p>NEO Web</p>
      </div>
      <div class="modal-footer bajar fondotitulo">
        <span class="izquierda letra11 abajo" id="mensajepie"></span>
        <button type="button" class="redondear btn-default" id="m_aceptar" data-dismiss="modal" onclick="mensajecerrar()">Aceptar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
