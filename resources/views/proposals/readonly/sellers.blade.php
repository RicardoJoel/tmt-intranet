<div id="tabsel" class="tabcontent" style="display:none">
    <div class="sub-table">
        <table id="tbl-sellers" class="tablealumno">
            <thead>
                <th width="40%">Nombre completo</th>
                <th width="10%">Código</th>
                <th width="40%">Detalle</th>
                <th width="10%">Comisión (%)</th>
            </thead>
            <tbody>
                @foreach ($sellers as $index => $sel)
                <tr>
                    <td>{{ $sel['user'] }}</td>
                    <td><center>{{ $sel['code'] }}</center></td>
                    <td>{{ $sel['detail'] }}</td>
                    <td><center>{{ $sel['commission'] ? number_format($sel['commission'],2) : '' }}</center></td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" style="text-align:right">Total (%):</th>
                    <th><center></center></th>
                </tr>
            </tfoot>			
        </table>
    </div>
</div>