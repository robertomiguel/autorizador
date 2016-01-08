<pre>
@if ( $promo <> 'no hay')
Promoción:<br>
{{$promo[0]->nombre}} <br>
Vencimiento: {{Formatos::fecha($promo[0]->vencimiento)}}
@else
	No hay promoción
@endif
</pre>

<pre>
@foreach ($detalle as $info)
 Solicitado: {{$info->solicitado}} 	<br>
       Tasa: {{$info->tasa}} 		<br>
    Interes: {{$info->interes}} 	<br>
      Total: {{$info->total}} 		<br>
Cant Cuotas: {{$info->cantcuotas}} 	<br>
Valor Cuota: {{$info->cuota}}
@endforeach
</pre>