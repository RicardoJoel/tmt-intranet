<div id="tabemp" class="tabcontent" style="display:block">
    <div class="sub-table">
        <table id="tbl-employees" class="tablealumno">
            <thead>
                <th width="30%">Perfil</th>
                <th width="30%">Detalle</th>
                <th width="15%">Costo x hr.</th>
                <th width="10%">N° Horas</th>
                <th width="15%">Subtotal (PEN)</th>
            </thead>
            <tbody>
                @foreach ($employees as $index => $emp)
                <tr>
                    <td>{{ $emp['profile'] }}</td>
                    <td>{{ $emp['detail'] }}</td>
                    <td><center>{{ $emp['hourly_rate'] ? number_format($emp['hourly_rate'],2) : '' }}<center></td>
                    <td><center>{{ $emp['num_hours'] }}</center></td>
                    <td><center>{{ $emp['subtotal'] ? number_format($emp['subtotal'],2) : '' }}<center></td>
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