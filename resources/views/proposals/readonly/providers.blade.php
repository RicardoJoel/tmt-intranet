<div id="tabpro" class="tabcontent" style="display:none">
    <div class="sub-table">
        <table id="tbl-providers" class="tablealumno">
            <thead>
                <th width="30%">Perfil</th>
                <th width="30%">Detalle</th>
                <th width="10%">Costo</th>
                <th width="15%">Moneda</th>
                <th width="15%">Subtotal (PEN)</th>
            </thead>
            <tbody>
                @foreach ($providers as $index => $pro)
                <tr>
                    <td>{{ $pro['profile'] }}</td>
                    <td>{{ $pro['detail'] }}</td>
                    <td><center>{{ $pro['price'] ? number_format($pro['price'],2) : '' }}</center></td>
                    <td><center>{{ $pro['currency'] }}</center></td>
                    <td><center>{{ $pro['subtotal'] ? number_format($pro['subtotal'],2) : '' }}</center></td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" style="text-align:right">Total (PEN):</th>
                    <th><center></center></th>
                </tr>
            </tfoot>			
        </table>
    </div>
</div>