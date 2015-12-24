<style>
	.cajon{
		position: absolute;
		width:  300px;
		height: 400px;
		top: 100px;
		right: 20px;
		background: white;
		z-index: 5000;
	}
	.piecaja{
		position: absolute;
		background: #ccc;
		width: 100%;
		height: 30px;
		bottom: 0;
		text-align: center;
	}
</style>
<div id="mostrarcaja" class="cajon sombra redondear">
	<div align='center' class="letra11 panel-heading fondocolor texto-sombra">
		DETALLE DE LA COMPRA
	</div>
	<div id="contenidocaja"></div>
	<div class="piecaja redondear">
		<button class="redondear btn-default" onclick="mostrarcaja()">
		<span class="glyphicon glyphicon-off"></span> CERRAR </button>
	</div>	

</div>
