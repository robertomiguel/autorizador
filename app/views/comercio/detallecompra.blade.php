<pre>
@if ( $promo <> 'no hay')
Promoción:<br>
{{$promo[0]->nombre}}<br>
Vencimiento: {{Formatos::fecha($promo[0]->vencimiento)}}
@else
	 No hay promoción
@endif
</pre>
<pre>
         {{Formatos::mostrarFecha()}}
</pre>

<pre>
@foreach ($detalle as $info)
 Solicitado: ${{Formatos::moneda($info->solicitado)}}<br>
    Interes: ${{Formatos::moneda($info->interes)}}<br>
      Total: ${{Formatos::moneda($info->total)}}<br>
Cant Cuotas: {{$info->cantcuotas}}<br>
Valor Cuota: ${{Formatos::moneda($info->cuota)}}
@endforeach
</pre>
{{--Tasa: {{$info->tasa}}%<br>--}}