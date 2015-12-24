function autorizar(){
	var pin = $('#pass').val();
	document.cookie="nro="+pin;
	window.location.assign('/autorizaciones');
}

function runScript(e) {
    if (e.keyCode == 13) {
        autorizar();
    }
}