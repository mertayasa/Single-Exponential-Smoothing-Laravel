@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{asset('admin/vendor/datatables_jquery/datatables.css')}}">
    <link rel="stylesheet" href="{{asset('plugin/sweetalert2/dist/sweetalert2.css')}}">
    <link rel="stylesheet" href="{{asset('plugin/iCheck/skins/flat/orange.css')}}">
@endpush
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Peramalan {{$product_name}}</h1>
    </div>
    <p><strong><a href="{{route('dashboard')}}" class='text-decoration-none text-gray-900'>Dashboard</a></strong> / Detail Peramalan / {{$product_name}} </p>
    <!-- Area Table -->
    {{-- @include('layouts.flash') --}}
    <div class="col-12 p-0">
        <div class="card shadow mb-4">
            <!-- Card Body -->
            <div class="card-body">
                <div class="col-12 p-0 mb-3">
                    <div class="row">
                        <div class="col-6 d-flex">
                            <a href="{{route('forecast.export_histori', ['excel', $product_id])}}" class="btn btn-primary mr-2">Export Excel</a>
                            <a href="{{route('forecast.export_histori', ['pdf', $product_id])}}" class="btn btn-success">Export Pdf</a>
                        </div>
                    </div>
                </div>
                {!! $dataTable->table(['width' => '100%']) !!}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{asset('admin/vendor/datatables_jquery/datatables.js')}}"></script>
<script src="{{asset('plugin/sweetalert2/dist/sweetalert2.js')}}"></script>
<script src="{{asset('plugin/iCheck/icheck.js')}}"></script>
@include('layouts.admin_js')
{!! $dataTable->scripts() !!}
@endpush
