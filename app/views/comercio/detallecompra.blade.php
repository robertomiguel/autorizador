<pre>
@foreach ($detalle as $info)
	 Cuotas: {{$info->cuotas}} <br>
	  Monto: {{$info->monto}} <br>
	Archivo: {{$info->archivo}} <br>
Valor Cuota: {{Formatos::moneda(($info->monto * 1) / ($info->cuotas))}}
@endforeach
</pre>