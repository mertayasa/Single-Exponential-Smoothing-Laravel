@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{asset('admin/vendor/datatables_jquery/datatables.css')}}">
    <link rel="stylesheet" href="{{asset('plugin/sweetalert2/dist/sweetalert2.css')}}">
    <link rel="stylesheet" href="{{asset('plugin/iCheck/skins/flat/orange.css')}}">
@endpush
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Management Data Aktual</h1>
    </div>
    <p><strong><a href="{{route('dashboard')}}" class='text-decoration-none text-gray-900'>Dashboard</a></strong> / Management Data Aktual</p>
    <!-- Area Table -->
    {{-- @include('layouts.flash') --}}
    <div class="col-12 p-0">
        <div class="card shadow mb-4">
            <!-- Card Body -->
            <div class="card-body">
                <div class="col-12 p-0 mb-3">
                    <div class="row">
                        <div class="col-8 align-items-start">
                            {{-- <button class="btn btn-warning mb-3 mr-2" onclick="location.href='{{route('subdivision_group.create')}}'">🞤 Create</button> --}}
                            <button type="button" class="btn btn-primary mb-3 mr-2" onclick="showCreateModal()" data-target="#actualDataModal" data-toggle="modal">+ Tambah Data</button>
                            <button class="btn btn-danger mb-3 mr-2" onclick="initDeleteSelection()"> <i class="fa fa-trash"></i> Hapus Pilihan</button>
                            <button class="btn btn-warning mb-3" onclick="window.location.href='{{url('forecast')}}'"> <i class="fas fa-search-dollar"></i> Mulai Peramalan </button>
                        </div>
                        <div class="col-6 mt-3 mb-3">
                            <div class="row align-items-center">
                                <div class="col-2">
                                    <span>Filter</span>
                                </div>
                                <div class="col-9">
                                    {!! Form::select('product_id', $product, null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {!! $dataTable->table(['width' => '100%']) !!}
            </div>
        </div>
    </div>

    <!-- Modal Select Property -->
    <div class="modal fade modal-create" id="actualDataModal" tabindex="-1" role="dialog" aria-labelledby="actualDataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="actualDataModalLabel">Tambah Data Aktual</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <form action="" id="productFrom">
                        {!! Form::hidden('actual_id', null, ['id' => 'actualDataId']) !!}
                        {!! Form::label('product_id', 'Produk') !!}
                        {!! Form::select('product_id', $product, isset($actual_data) ? $actual_data->product_id : 0, ['class' => 'form-control', 'id' => 'productId', 'onChange' => 'triggerMonth(this.value)']) !!}
                        {!! Form::label('actual_data', 'Data Aktual', ['class' => 'mt-4']) !!}
                        {!! Form::number('actual', null, ['class' => 'form-control', 'id' => 'actualData', 'disabled']) !!}
                        {!! Form::label('month', 'Bulan', ['class' => 'mt-4']) !!}
                        <select name="month_id" id="monthList" class="form-control" disabled>
                            <option value="0">Pilih Bulan</option>
                        </select>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-warning btn-submit">Save</button>
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

    let actualIdField = $('#actualDataId');
    let productIdField = $('#productId');
    let productActualDataField = $('#actualData');
    let categorySelect = $('#productCategory');
    let monthSelect = $('#monthList');
    let buttonSubmit = $('.btn-submit');
    let closeButton = $('.close');

    // Delete Multiple Data
    function initDeleteSelection(){
        let deleteUrl = "{{ url('actual_data_destroy') }}";
        deleteSelection(deleteUrl)
    }

    // Delete Single Data
    function initDeleteSingle(id){
        let deleteUrl = "{{ url('actual_data_destroy') }}"
        deleteSingle(id, deleteUrl)
    }

    // Show create modal and fill it with data
    function showCreateModal(){
        buttonSubmit.attr('onclick', "submitActualData('create')")
    }

    // Clear Form when button close modal clicked
    closeButton.on('click', function(){
        clearForm();
    })

    // Get next one month value
    function triggerMonth(value){
        let xcsrf = "{{csrf_token()}}";

        if(value == 0){
            productActualDataField.prop('disabled', true)
            monthSelect.prop('disabled', true)
            monthSelect.html(`<option value="0">Pilih Bulan</option>`);
        }else{
            monthSelect.prop('disabled', false)
            productActualDataField.prop('disabled', false)
            $.ajax({
                url : "{{url('actual_data_month')}}" + '/' + value,
                method : 'get',
                headers : xcsrf,
                dataType : 'json',
                success : function(data){
                    monthSelect.html('');
                    monthSelect.append(`<option value="` + data[0].id + `"> ` + data[0].month + ` </option>`)
                    // $.each(data, function(key, value) {
                    //     monthSelect.append(`<option value="` + value.id + `"> ` + value.month + ` </option>`)
                    // });
                }
            })
        }
    }

    // Submit Form
    function submitActualData(url){
        let formData = $('#productFrom').serializeArray();
        let csrf_token = "{{ csrf_token() }}";
        let method = '';
        let urlMethod = '';
        
        if(url == 'create'){
            method = 'post';
            urlMethod = "{{ url('actual_data/store')}}";
        }else{
            method = 'patch';
            urlMethod = "{{ url('actual_data_update')}}" + '/' + actualIdField.val();
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
                clearForm()
                let table = $('#'+tableId).DataTable()
                table.draw()
            }
        })
    }

    // Function to clear form
    function clearForm(){
        $('#productFrom')[0].reset();
        productActualDataField.prop('disabled', true)
        monthSelect.prop('disabled', true)
        monthSelect.html(`<option>Pilih Bulan</option>`)
    }

    // Retrieve data from db for update purpose
    function updateCategory(id){
        $('.modal-create').modal('show');
        $('.btn-back-properties').hide();
        buttonSubmit.attr('onclick', "submitActualData('update')")
        let csrf_token = "{{csrf_token()}}"

        $.ajax({
            url : "{{url('actual_data')}}" + '/' + id,
            method : 'get',
            dataType : 'json',
            headers: {'X-CSRF-TOKEN': csrf_token},
            success: function(data){
                console.log(data)
                actualIdField.val(data.id)
                productIdField.html('');
                productIdField.prop('disabled', true);
                productIdField.append(`<option value="` + data.product.id + `"> ` + data.product.product_name + ` </option>`)
                productActualDataField.prop('disabled', false)
                productActualDataField.val(data.actual)
                monthSelect.html('');
                monthSelect.append(`<option value="` + data.month.id + `"> ` + data.month.month + ` </option>`)
            }

        })
    }

</script>
{!! $dataTable->scripts() !!}
@endpush
