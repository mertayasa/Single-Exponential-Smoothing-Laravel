<table class="table table-bordered table-condensed table-striped" style="max-width:100%;">
    <tr>
        <td colspan="4" style="text-align: center; font-family:ipaexg">Rangkuman Peramalan</td>
    </tr>
    <tr>
        <td>Nama Menu</td>
        <td>Bulan</td>
        <td>Peramalan</td>
    </tr>
    @foreach($export_data as $data)
        <tr>
            <td>{{$data['menu']['menu_name']}}</td>
            <td>{{$data['month']['month']}}</td>
            <td>{{$data['actual']}}</td>
            {{-- <td>{{$data['menu']['menu_name']}}</td>
            <td>{{$data['month']['month'].' '.$data['year']}}</td>
            <td>{{$data['forecast']}}</td> --}}
        </tr>
    @endforeach
</table>
