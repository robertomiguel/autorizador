<pre>
@foreach ($detalle as $info)
	 Cuotas: {{$info->cuotas}} <br>
	  Monto: {{$info->monto}} <br>
Valor Cuota: {{$info->valorcuota}} <br>
@endforeach
</pre>