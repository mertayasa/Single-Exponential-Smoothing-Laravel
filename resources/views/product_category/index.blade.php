@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{asset('admin/vendor/datatables_jquery/datatables.css')}}">
    <link rel="stylesheet" href="{{asset('plugin/sweetalert2/dist/sweetalert2.css')}}">
    <link rel="stylesheet" href="{{asset('plugin/iCheck/skins/flat/orange.css')}}">
@endpush
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Management Kategori</h1>
    </div>
    <p><strong><a href="{{route('dashboard')}}" class='text-decoration-none text-gray-900'>Dashboard</a></strong> / Management Kategori</p>
    <!-- Area Table -->
    {{-- @include('layouts.flash') --}}
    <div class="col-12 p-0">
        <div class="card shadow mb-4">
            <!-- Card Body -->
            <div class="card-body">
                <div class="col-12 p-0 mb-3">
                    <div class="row">
                        <div class="col-6 align-items-start">
                            {{-- <button class="btn btn-warning mb-3 mr-2" onclick="location.href='{{route('subdivision_group.create')}}'">🞤 Create</button> --}}
                            <button type="button" class="btn btn-warning mb-3 mr-2" onclick="showCreateModal()" data-target="#createCategoryModal" data-toggle="modal">+ Tambah Data</button>
                            <button class="btn btn-danger mb-3" onclick="initDeleteSelection()"> <i class="fa fa-trash"></i> Hapus Pilihan</button>
                        </div>
                        <div class="col-6 d-flex">
                        </div>
                    </div>
                </div>
                {!! $dataTable->table(['width' => '100%']) !!}
            </div>
        </div>
    </div>

    <!-- Modal Select Property -->
    <div class="modal fade modal-create" id="createCategoryModal" tabindex="-1" role="dialog" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="createCategoryModalLabel">Tambah Kategori</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <form action="" id="categoryFrom">
                        <input type="hidden" name="category_id" id="categoryId">
                        {!! Form::label('category_name', 'Nama Kategori') !!}
                        <input type="text" class="form-control" name="category_name" id="categoryName">
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-warning btn-submit">Save</button>
                    {{-- <button class="btn btn-warning btn-submit" onclick="submitSubdivisionGroup()">Save</button> --}}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{asset('admin/vendor/datatables_jquery/datatables.js')}}"></script>
<script src="{{asset('plugin/sweetalert2/dist/sweetalert2.js')}}"></script>
<script src="{{asset('plugin/iCheck/icheck.js')}}"></script>
@include('layouts.admin_js')
<script>

    // import swal from 'sweetalert';

    function initDeleteSelection(){
        let deleteUrl = "{{ url('product_category_destroy') }}";
        deleteSelection(deleteUrl)
    }

    function initDeleteSingle(id){
        let deleteUrl = "{{ url('product_category_destroy') }}"
        deleteSingle(id, deleteUrl)
    }

    let categoryIdField = $('#categoryId');
    let categoryNameField = $('#categoryName');
    let buttonSubmit = $('.btn-submit');

    // Show create modal and fill it with data
    function showCreateModal(){
        buttonSubmit.attr('onclick', "submitSubdivisionGroup('create')")
    }

    function showEditModal(){
        buttonSubmit.attr('onclick', "submitSubdivisionGroup('create')")
    }

    function submitSubdivisionGroup(url){
        let formData = $('#categoryFrom').serializeArray();
        let csrf_token = "{{ csrf_token() }}";
        let method = '';
        let urlMethod = '';
        
        if(url == 'create'){
            method = 'post';
            urlMethod = "{{ url('product_category/store')}}";
        }else{
            method = 'patch';
            urlMethod = "{{ url('product_category_update')}}" + '/' + categoryIdField.val();
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
            url : "{{url('product_category')}}" + '/' + id,
            method : 'get',
            dataType : 'json',
            headers: {'X-CSRF-TOKEN': csrf_token},
            success: function(data){
                console.log(data)
                categoryIdField.val(data.id);
                categoryNameField.val(data.category_name);
            }

        })
    }

</script>
{!! $dataTable->scripts() !!}
@endpush
