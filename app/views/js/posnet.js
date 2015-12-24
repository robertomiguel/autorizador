$(document).ready(function() {
    $('#mostrarcaja').hide();
    $('#nro_tarjeta').focus();
	  /*$('#cant_cuotas').prop( "disabled", true );
    $('#importe').prop( "disabled", true );
    $('#cupon').prop( "disabled", true );*/
    $('#contado').click(function(){contado();});
    $('#cuotas').click(function(){cuotas();});
});

function cerrar(){
  window.location.href = "/salir";
}


$("#nro_tarjeta").keyup(function(event) {
  //alert(event.which); return;
 /* if (   (event.which >= 48 && event.which <= 57)
      || (event.which >= 96 && event.which <= 105)
      || $('#nro_tarjeta').val().length > 5 ) {
    validarTarjeta();
  }*/
  switch (event.which) {
  	case 13: //enter
    case 40: //abajo
        validarTarjeta();
        $('#contado').focus();

    break;
  }
});

$("#contado").keyup(function(event) {
  switch (event.which) {
  	case 13: //enter
			//contado();
    break;  	
    case 40: //abajo
    		$('#importe').focus();
    break;
    case 38: //arriba
    		$('#nro_tarjeta').focus();
    break;
    case 39: //derecha
    		$('#cuotas').focus();
    break;
  }
});

var pago;

function contado(){
    pago = 1;
    $("#cuotas_icon").removeClass('glyphicon-pencil');
    $("#cuotas_icon").removeClass('glyphicon-remove');
    $("#cuotas_icon").addClass('glyphicon-ok');
    $('#contado').attr('class', 'form-control horizontal btn-primary');
    $('#cuotas').attr('class', 'derecha form-control btn-default');
	  //$('#estado_seleccion').text('CONTADO');
	  $('#cant_cuotas').val('1');
    $('#cant_cuotas').prop( "disabled", true );
    $('#importe').prop( "disabled", false );
	  $('#importe').focus();
}

$("#cuotas").keyup(function(event) {
  switch (event.which) {
  	case 13: //enter
  			cuotas();
  	break;
    case 40: //abajo
    	    $('#cant_cuotas').focus();
    break;
    case 38: //arriba
    		$('#nro_tarjeta').focus();
    break;
    case 37: //izquierda
    		$('#contado').focus();
    break;
  }
});

function cuotas(){
      pago = 2;
    $('#contado').attr('class', 'form-control horizontal btn-default');
    $('#cuotas').attr('class', 'derecha form-control btn-primary');
    var cuotas = $('#cant_cuotas').val() * 1;
    if (cuotas<=1) {
        $('#cant_cuotas').val('2');
    };
    $('#cant_cuotas').select();    
//	$('#estado_seleccion').text('CUOTAS');
	$('#cant_cuotas').prop( "disabled", false );
  $('#cant_cuotas').focus();
}

$("#cant_cuotas").keyup(function(event) {
  switch (event.which) {
    case 13: //enter
      $('#importe').prop( "disabled", false );

          validarCuotas();
    break;
    case 40: //abajo
          $('#importe').focus();
          validarCuotas();
    break;
    case 38: //arriba
        $('#cuotas').focus();
    break;
  }
});

$("#cant_cuotas").keydown(function(event) {
  switch (event.which) {
  	case 13: //enter

	        $('#importe').focus();
          validarCuotas();
  	break;
    case 40: //abajo
    	    $('#importe').focus();
          validarCuotas();
    break;
  }
});

$("#importe").keyup(function(event) {
  switch (event.which) {
    case 13: //enter
  	        $('#cupon').prop( "disabled", false );
    break;
    case 38: //arriba
          if (pago==1) {
        $('#contado').focus();
      } else {
        $('#cant_cuotas').focus();
      }
      validarImporte();
    break;
    
  }

});

$("#importe").keydown(function(event) {
  switch (event.which) {
    case 13: //enter
	        $('#cupon').focus();
          validarImporte();

  	break;
    case 40: //abajo
          $('#cupon').focus();
          validarImporte();
    break;
  }
});

$("#cupon").keydown(function(event) {
  switch (event.which) {
    case 13: //enter
          validarCupon();
          //$('.f_amarillo').focus();
    break;
    case 40: //abajo
          $('.f_amarillo').focus();
          validarCupon();
    break;
  }
});

$("#cupon").keyup(function(event) {
  switch (event.which) {
//    case 13: //enter
  //  break;
    case 38: //arriba
    		$('#importe').focus();
        validarCupon();
    break;
  }
});

$(".f_amarillo").keyup(function(event) {
  switch (event.which) {
  	case 13: //enter
	        //autorizar();
  	break;
    case 39: //derecha
        $('.f_rojo').focus();
    break;
    case 38: //arriba
    		$('#cupon').focus();
    break;
  }
});

$(".f_rojo").keydown(function(event) {
  switch (event.which) {
    case 37: // izquierda
        $('.f_amarillo').focus();
    break;
    case 38: //arriba
        $('#cupon').focus();
    break;
  }
});

//-----------------------------------------------------------
function validarTarjeta() {
  if ($('#nro_tarjeta').val()=='') {return;}
      $('#estado_tarjeta').text('Validando...');

  $.post("autorizaciones/validar-tarjeta",
      {
      nro_tarjeta: $('#nro_tarjeta').val()
      },
      function(data){

          var r = data.split('|');
          $('#estado_tarjeta').html('');

          if (r[1]=='Correcto') {
            $("#tarjeta_icon").removeClass('glyphicon-pencil');
            $("#tarjeta_icon").removeClass('glyphicon-remove');
            $("#tarjeta_icon").addClass('glyphicon-ok');
            //$('#estado_ocio').text('Correcto');

          } else {
            $("#tarjeta_icon").removeClass('glyphicon-pencil');
            $("#tarjeta_icon").removeClass('glyphicon-ok');
            $("#tarjeta_icon").addClass('glyphicon-remove');
            $('#nro_tarjeta').focus();

          }
            if (data==0){
              $('#nombre').text('');
              $('#estado_tarjeta').text('No existe la tarjeta');


            } else {
              $('#nombre').text(r[0]);
              $('#estado_tarjeta').text('');
            }


      }); 
}

function validarCuotas () {
  if ($('#cant_cuotas').val()=='') {return;}
  $('#estado_cuotas').text('Validando...');

  $.post("autorizaciones/validar-cuotas",
      {
      cuotas: $('#cant_cuotas').val()
      },
      function(data){
        
        if (data=='Correcto') {
//          $('#estado_cuotas').html('<img src="img/ok.gif" class="mini"/> '+ data);
            $("#cuotas_icon").removeClass('glyphicon-pencil');
            $("#cuotas_icon").removeClass('glyphicon-remove');
            $("#cuotas_icon").addClass('glyphicon-ok');
            $('#estado_cuotas').html('');

        } else {
            $("#cuotas_icon").removeClass('glyphicon-pencil');
            $("#cuotas_icon").removeClass('glyphicon-ok');
            $("#cuotas_icon").addClass('glyphicon-remove');
            $('#estado_cuotas').html(data);
            $('#cant_cuotas').focus();
        }

      }); 
}

function validarImporte() {
  if ($('#importe').val() * 1 == 0) {return;}
  $('#estado_importe').text('Validando...');
  $.post("autorizaciones/validar-importe",
      {
      nro_tarjeta: $('#nro_tarjeta').val(),
      importe: $('#importe').val(),
      pago: pago
      },
      function(data){

        if (data=='Correcto') {
//          $('#estado_cuotas').html('<img src="img/ok.gif" class="mini"/> '+ data);
            $("#importe_icon").removeClass('glyphicon-pencil');
            $("#importe_icon").removeClass('glyphicon-remove');
            $("#importe_icon").addClass('glyphicon-ok');
            $('#estado_importe').html('');

            detallecompra($('#cant_cuotas').val(),$('#importe').val());

        } else {
            $("#importe_icon").removeClass('glyphicon-pencil');
            $("#importe_icon").removeClass('glyphicon-ok');
            $("#importe_icon").addClass('glyphicon-remove');
            $('#estado_importe').html(data);
            $('#importe').focus();
         
        }

  }); 

}

function validarCupon(){
  if ($('#cupon').val() * 1 == 0) {return;}
  $('#estado_cupon').text('Validando...');
  $.post("autorizaciones/validar-cupon",
      {
      cupon: $('#cupon').val()
      },
      function(data){
        
        if (data=='Correcto') {
//          $('#estado_cuotas').html('<img src="img/ok.gif" class="mini"/> '+ data);
            $("#cupon_icon").removeClass('glyphicon-pencil');
            $("#cupon_icon").removeClass('glyphicon-remove');
            $("#cupon_icon").addClass('glyphicon-ok');
            $('#estado_cupon').html('');

        } else {
            $("#cupon_icon").removeClass('glyphicon-pencil');
            $("#cupon_icon").removeClass('glyphicon-ok');
            $("#cupon_icon").addClass('glyphicon-remove');
            $('#estado_cupon').html(data);
            $('#cupon').focus();
         
        }

  }); 

}

var validar  = 0;

function autorizar() {
	$(".f_amarillo").prop("disabled",true);
  $(".f_amarillo").html(' <span class="glyphicon glyphicon-hourglass"></span> Autorizando... ');
  var nro_tarjeta	= $('#nro_tarjeta').val();
	var cuotas 		  = $('#cant_cuotas').val();
	var importe 	  = $('#importe').val();
	var cupon 		  = $('#cupon').val();

 	$.post("validar",
	    {
	    nro_tarjeta: nro_tarjeta,
	           pago: pago,
	         cuotas: cuotas,
	        importe: importe,
	          cupon: cupon
	    },
	function(data){
          $('#mensajepie').html('');
          if (data.substr(0,20)=="Código de Autorizaci"){
              mensajecaja("Transacción Completa:",data,"ok.png")
              validar = 1;
            } else {
              mensajecaja("Notificación:",data,"alert.png")
          }
          $("#m_aceptar").focus();
          $(".f_amarillo").html(' <span class="glyphicon glyphicon-ok"></span> AUTORIZAR ');
          $(".f_amarillo").prop("disabled",false);
	  
	});	
} //--------- fin autorizar

function operaciones() {

  $('#mensajetitulo').html("Operaciones del día");
  $('#mensajetexto').html('Consultando...');
  $('#mensajecaja').modal('show');

  $.post("autorizaciones/operaciones",{},
    function(data){
    $('#mensajetexto').html(data);
  });

}

function reiniciar(){
  location.reload(); 
}

function mensajecaja(titulo,mensaje,icono){
  $('#mensajetitulo').html(titulo);
  $('#mensajetexto').html("<img src='/img/"+icono+"'/><br><br>"+mensaje);
  $('#mensajecaja').modal('show');
}

function mensajecerrar(){
  if (validar==1) {
    location.reload();
  }
}

function mostrarcaja(){
  $('#mostrarcaja').toggle();
}

function detallecompra(cuotas, monto) {

  $('#contenidocaja').html('Calculando...');

  $.post("autorizaciones/detallecompra",{
    cuotas: cuotas,
    monto: monto
  },
  function(data){
    $('#contenidocaja').html(data);
  });
}