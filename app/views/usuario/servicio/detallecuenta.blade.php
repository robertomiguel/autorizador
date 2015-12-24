<style>
  .modal-dialog, .modal-content {
    height: 95%;
    width: 100%;
}

.modal-body {
    max-height: calc(100% - 120px);
    overflow-y: scroll;
}
.latabla {
        width: 700px;
}
.latabla tr:nth-child(even) {background: #CCC}
.latd {
        background: #CCC;
}
</style>

<table class="latabla letra14">
        <thead>
                <tr class="latd">
                        <td align="center" width="90px">Fecha</td>
                        <td align="center" width="150px">Concepto</td>
                        <td align="right" width="100px">Crédito</td>
                        <td align="right" width="100px">Débito</td>
                        <td align="right" width="100px">Saldo</td>
                        <td width="30px"></td>
                </tr>
        </thead>
        <tbody>
                @foreach(json_decode($informe) as $mov)
                        <tr>
                                <td width="90px" align="center">
                                        {{Formatos::fecha($mov->fecha)}}<br>
                                </td>
                                <td width="150px">
                                        {{$mov->concepto}}<br>
                                </td width="100px">
                                @if (Formatos::moneda($mov->importe) < 0)
                                <td align="right">
                                </td>
                                <td align="right"  width="100px">
                                        {{Formatos::moneda($mov->importe)}}<br>
                                </td>
                                @else
                                <td align="right" width="100px">
                                        {{Formatos::moneda($mov->importe)}}<br>
                                </td>
                                <td align="right">
                                </td>
                                @endif
                                <td align="right" width="100px">
                                        {{Formatos::moneda($mov->saldo)}}<br>
                                </td>
                                <td  width="20px">
                                </td>
                        </tr>
                @endforeach
        </tbody>
</table>

<script>
        $('.detallecel').dataTable({
            "scrollY"           :  400,
            "scrollCollapse"    : true,
            "ordering"          : false,
            "jQueryUI"          : false,
            "bDestroy"          : true,
            "pagingType": "full_numbers"
        });
</script>