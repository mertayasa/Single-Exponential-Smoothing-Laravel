<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="{{asset('admin/css/sb-admin-2.css')}}" rel="stylesheet">
    <title>Rangkuman Peramalan</title>
</head>
<body>
    <div class="container">
        <table>
            <tr>
                <td colspan="3" style="text-align: center; font-size:1.4rem"> <b>Rangkuman Peramalan</b> </td>
            </tr>
            <tr>
                <th>Nama Menu</th>
                <th>Bulan</th>
                <th>Peramalan</th>
            </tr>
            @foreach($export_data as $data)
                <tr>
                    <td>{{$data['menu']['menu_name']}}</td>
                    <td>{{$data['month']['month']}}</td>
                    <td>{{$data['forecast']}}</td>
                </tr>
            @endforeach
        </table>
    </div>

    <style>
        table{
            width: 100%;
            font-style: sans-serif;
            border-collapse: collapse;
        }

        table, th, td{
            border: 1px solid black;
        }

        th, td {
            padding: 15px;
            text-align: left;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>

    <script src="{{asset('admin/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('admin/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
</body>
</html>