<div id="tabfre" class="tabcontent" style="display:none">
    <div class="sub-table">
        <table id="tbl-independents" class="tablealumno">
            <thead>
                <th width="30%">Perfil</th>
                <th width="30%">Detalle</th>
                <th width="10%">Costo</th>
                <th width="15%">Moneda</th>
                <th width="15%">Subtotal (PEN)</th>
            </thead>
            <tbody>
                @foreach ($independents as $index => $ind)
                <tr>
                    <td>{{ $ind['profile'] }}</td>
                    <td>{{ $ind['detail'] }}</td>
                    <td><center>{{ $ind['price'] ? number_format($ind['price'],2) : '' }}</center></td>
                    <td><center>{{ $ind['currency'] }}</center></td>
                    <td><center>{{ $ind['subtotal'] ? number_format($ind['subtotal'],2) : '' }}</center></td>
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