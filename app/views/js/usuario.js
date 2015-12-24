$(document).ready(function() {

	  $('#acordeon').accordion({
      	collapsible: true,
      	heightStyle: "content",
      	active: false
	  });


//--- Cambio de pass
	$('#cambiarpass').dialog({
      resizable: false,
      height:350,
      modal: true,
      autoOpen: false,
      buttons: {
        "Aceptar": function() {
          $( this ).dialog( "close" );
        },
        Cancelar: function() {
          $( this ).dialog( "close" );
        }
      }
    });
//--- ver detalle cuenta
	$('#detalleCuenta').dialog({
      resizable: false,
      width:740,
      height:550,
      modal: true,
      autoOpen: false,
      close: function( event, ui ) {$("#resumen").html("");}
      /*buttons: {
        "Aceptar": function() {
          $( this ).dialog( "close" );
        }
      }*/
    });

});




function cambiarpass(){
	$('#cambiarpass').dialog("open");
}

function verDetalle($cuenta){

	$('#detalleCuenta').dialog("open");
	    
	    $("#cargando").attr("src","/img/cargando.gif");
	    

		//$("#ui-id-12").text("Detalle de Cuenta Nro. " + $cuenta);
          tabla.fnDestroy();
//	tabla.clear().draw();
  
    $.ajax({url: "/usuario/cuenta-detalle/"+$cuenta, success: function(datos){
        
        $("#cargando").attr("src","/img/invisible.png");

        $("#resumen").html(datos);

        $('#tabla_detalle_cuenta').dataTable({
        	"scrollY" :  400,
			"scrollCollapse": true,
			"ordering": true,
			"jQueryUI": true,
            "bDestroy": true,
            "pagingType": "full_numbers",
            "order": [ 0, "desc" ],
            "columnDefs": [
	            {
	                "targets": [ 0 ],
	                "visible": false,
	                "searchable": false
	            }
        	]
        });
        


    	}});
}

function verDetallePro(cuenta) {
  $('#mensajetitulo').html("Detalle de cuenta: " + cuenta);
  $('#mensajetexto').html('Consultando...');
  $('#mensajecaja').modal('show');

  $.post("socio/detalle-cuenta-pro",{
    cuenta : cuenta
  },
    function(data){
    $('#mensajetexto').html(data);
  });
}

var tabla = $('#tabla_detalle_cuenta').dataTable()

function detallecuenta(cuenta) {

  $('#mensajetitulo').html("Detalle de cuenta: " + cuenta);
  $('#mensajetexto').html('Consultando...');
  $('#mensajecaja').modal('show');

  $.post("socio/detalle-cuenta",{
    cuenta : cuenta
  },
    function(data){
    $('#mensajetexto').html(data);
  });

}


function verSiguiente(){

  $('#mensajetitulo').html("Consumos del próximo resumen:");
  $('#mensajetexto').html('Consultando...');
  $('#mensajecaja').modal('show');

  $.post("socio/consumos-siguientes",{
    
  },
    function(data){
    $('#mensajetexto').html(data);
  });

}

function verConsumoActual(){

  $('#mensajetitulo').html("Consumos:");
  $('#mensajetexto').html('Consultando...');
  $('#mensajecaja').modal('show');

  $.post("socio/consumos-actuales",{
    
  },
    function(data){
    $('#mensajetexto').html(data);
  });

}

function verConsumosPro(){

  $('#mensajetitulo').html("Consumos:");
  $('#mensajetexto').html('Consultando...');
  $('#mensajecaja').modal('show');

  $.post("socio/consumos-actuales-pro",{
    
  },
    function(data){
    $('#mensajetexto').html(data);
  });

}
