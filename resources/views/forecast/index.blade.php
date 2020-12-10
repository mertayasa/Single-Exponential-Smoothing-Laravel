@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{asset('admin/vendor/datatables_jquery/datatables.css')}}">
    <link rel="stylesheet" href="{{asset('plugin/sweetalert2/dist/sweetalert2.css')}}">
    <link rel="stylesheet" href="{{asset('plugin/iCheck/skins/flat/orange.css')}}">
@endpush
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Hasil Peramalan</h1>
    </div>
    <p><strong><a href="{{route('dashboard')}}" class='text-decoration-none text-gray-900'>Dashboard</a></strong> / Hasil Peramalan</p>
    <!-- Area Table -->
    {{-- @include('layouts.flash') --}}
    <div class="col-12 p-0">
        <div class="card shadow mb-4">
            <!-- Card Body -->
            <div class="card-body">
                <div class="col-12 p-0 mb-3">
                    <div class="row">
                        <div class="col-6 d-flex">
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
{{-- <script>

    // import swal from 'sweetalert';

    function initDeleteSelection(){
        let deleteUrl = "{{ url('product_destroy') }}";
        deleteSelection(deleteUrl)
    }

    function initDeleteSingle(id){
        let deleteUrl = "{{ url('product_destroy') }}"
        deleteSingle(id, deleteUrl)
    }

    let productIdField = $('#productId');
    let productNameField = $('#productName');
    let categorySelect = $('#productCategory');
    let buttonSubmit = $('.btn-submit');

    // Show create modal and fill it with data
    function showCreateModal(){
        buttonSubmit.attr('onclick', "submitSubdivisionGroup('create')")
    }

    function showEditModal(){
        buttonSubmit.attr('onclick', "submitSubdivisionGroup('create')")
    }

    function submitSubdivisionGroup(url){
        let formData = $('#productFrom').serializeArray();
        let csrf_token = "{{ csrf_token() }}";
        let method = '';
        let urlMethod = '';
        
        if(url == 'create'){
            method = 'post';
            urlMethod = "{{ url('product/store')}}";
        }else{
            method = 'patch';
            urlMethod = "{{ url('product_update')}}" + '/' + productIdField.val();
        }

        $.ajax({
            headers: {'X-CSRF-TOKEN': csrf_token},
            url : urlMethod,
            dataType : "Json",
            method : method,
            data : formData,
            success: function(data){
                console.log(data)
                if(data[0] == 1){
                    $('.modal-create').modal('hide');
                    Swal.fire(
                        'Success',
                        data[1],
                        'success'
                    )}else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: data[1]
                        })
                    }
                let table = $('#'+tableId).DataTable()
                table.draw()
            }
        })
    }

    function updateCategory(id){
        $('.modal-create').modal('show');
        $('.btn-back-properties').hide();
        buttonSubmit.attr('onclick', "submitSubdivisionGroup('update')")
        let csrf_token = "{{csrf_token()}}"

        $.ajax({
            url : "{{url('product')}}" + '/' + id,
            method : 'get',
            dataType : 'json',
            headers: {'X-CSRF-TOKEN': csrf_token},
            success: function(data){
                console.log(data)
                productIdField.val(data.id);
                productNameField.val(data.product_name);
                categorySelect.val(data.product_category_id)
            }

        })
    }

</script> --}}
{!! $dataTable->scripts() !!}
@endpush
