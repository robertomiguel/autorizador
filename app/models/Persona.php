<?php

class Persona extends Eloquent {

	protected $table = 'personas';
	protected $primaryKey = 'nro_persona';
	public $timestamps = false;

	public function getNombreCompleto (){
		return $this->apellido.' '.$this->nombre;
	}

	static public function nombreCompleto (){
		$nro_persona = Auth::user()->nro_persona;
		$nombre = Persona::find($nro_persona)->nombre;
		$apellido = Persona::find($nro_persona)->apellido;
		return Formatos::capital($apellido.' '.$nombre);
	}

	static public function numeroSocio (){
		$nro_persona = Auth::user()->nro_persona;
		return Persona::find($nro_persona)->soc_numero_socio;
	}

	static public function numeroComercio (){
		$nro_persona = Auth::user()->nro_persona;
		return Persona::find($nro_persona)->com_numero_comercio;
	}

	static public function socioEstado (){
		$nro_persona = Auth::user()->nro_persona;
		return Persona::find($nro_persona)->soc_estado;
	}

	static public function comercioEstado (){
		$nro_persona = Auth::user()->nro_persona;
		return Persona::find($nro_persona)->com_estado;
	}

	static public function nombreComercio (){
		$nro_persona = Auth::user()->nro_persona;
		$nombre = Persona::find($nro_persona)->com_nombre_fantasia;
		if ( count($nombre) == 0) {
			$nombre = Persona::find($nro_persona)->com_razon_social;
		}
		return $nombre;
	}

	static public function usaTarjeta(){
		$nro_persona = Auth::user()->nro_persona;
		$sql = " SELECT personas.nro_persona 
				   FROM personas
				   JOIN tarjetas_cuentas ON
				        ( tarjetas_cuentas.numero_socio = personas.soc_numero_socio )
  				  WHERE personas.nro_persona = $nro_persona
  			   ";

  		$datos = DB::select($sql);
  		
  		if (count($datos) > 0) {
  			return true;
  		}
  		Return false;
	}

	//---------- PUEBA
	static public function listaSocios(){
	
		$personas = DB::select("
        						SELECT soc_numero_socio as a1, (apellido + ' ' + nombre) as a2, email as a3
        						  FROM personas 
        						 WHERE es_socio = 1
        						");
		return $personas;
	}

	static public function listaComercios(){
	
		$personas = DB::select("
        						SELECT com_numero_comercio as a1, com_razon_social as a2, email as a3
        						  FROM personas 
        						 WHERE es_comercio = 1
        						");
		return $personas;
	}
}