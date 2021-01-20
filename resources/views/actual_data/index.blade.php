@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{asset('admin/vendor/datatables_jquery/datatables.css')}}">
    <link rel="stylesheet" href="{{asset('plugin/sweetalert2/dist/sweetalert2.css')}}">
    <link rel="stylesheet" href="{{asset('plugin/iCheck/skins/flat/orange.css')}}">
@endpush
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Aktual Data</h1>
    </div>
    <p><strong><a href="{{route('dashboard')}}" class='text-decoration-none text-gray-900'>Dashboard</a></strong> / Manajemen Aktual Data</p>
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
                        </div>
                        <div class="col-6 mt-3 mb-3">
                            <div class="row align-items-center">
                                <div class="col-2">
                                    <span>Filter</span>
                                </div>
                                <div class="col-9">
                                    {!! Form::select('menu_id_filter', $menu, null, ['class' => 'form-control', 'id' => 'filtermenu']) !!}
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
                    <form action="" id="menuFrom">
                        {!! Form::hidden('actual_id', null, ['id' => 'actualDataId']) !!}
                        {!! Form::label('menu_id', 'Produk') !!}
                        {!! Form::select('menu_id', $menu, isset($actual_data) ? $actual_data->menu_id : 0, ['class' => 'form-control', 'id' => 'menuId', 'onChange' => 'triggerMonth(this.value)']) !!}
                        {!! Form::label('actual_data', 'Data Aktual', ['class' => 'mt-4']) !!}
                        {!! Form::number('actual', null, ['class' => 'form-control', 'id' => 'actualData', 'disabled', 'onKeyUp="validate(this, event)"']) !!}
                        {!! Form::hidden('year', null, ['class' => 'form-control', 'id' => 'yearData']) !!}
                        {!! Form::label('month', 'Bulan', ['class' => 'mt-4']) !!}
                        <select name="month_id" id="monthList" class="form-control" disabled onChange="changeYearValue()">
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

    {{-- <style>
        .dataTables_filter>label>.form-control{
            border: 1px solid red !important;
        }
    </style> --}}
@endsection

@push('scripts')
<script src="{{asset('admin/vendor/datatables_jquery/datatables.js')}}"></script>
<script src="{{asset('plugin/sweetalert2/dist/sweetalert2.js')}}"></script>
<script src="{{asset('plugin/iCheck/icheck.js')}}"></script>
@include('layouts.admin_js')
<script>

    let filtermenu = $('#filtermenu');
    let actualIdField = $('#actualDataId');
    let menuIdField = $('#menuId');
    let yearDataField = $('#yearData');
    let menuActualDataField = $('#actualData');
    let categorySelect = $('#menuCategory');
    let monthSelect = $('#monthList');
    let buttonSubmit = $('.btn-submit');
    let closeButton = $('.close');
    let modalHeader = $('#actualDataModalLabel');
    let timeSeriesData = [];

    // Delete Multiple Data
    function initDeleteSelection(){
        let deleteUrl = "{{ url('actual-data-destroy') }}";
        deleteSelection(deleteUrl)
    }

    // Delete Single Data
    function initDeleteSingle(id){
        let deleteUrl = "{{ url('actual-data-destroy') }}"
        deleteSingle(id, deleteUrl)
    }

    // Show create modal and fill it with data
    function showCreateModal(){
        buttonSubmit.attr('onclick', "submitActualData('create')")
        buttonSubmit.text('Save');
        modalHeader.text('Tambah Aktual Data');
    }

    function showEditModal(){
        buttonSubmit.attr('onclick', "submitActualData('update')")
        buttonSubmit.text('Update');
        modalHeader.text('Update Aktual Data');
    }

    // Clear Form when button close modal clicked
    closeButton.on('click', function(){
        clearForm();
    })

    // Get next one month value
    function triggerMonth(value){
        let xcsrf = "{{csrf_token()}}";

        if(value == 0){
            menuActualDataField.prop('disabled', true)
            monthSelect.prop('disabled', true)
            monthSelect.html(`<option value="0">Pilih Bulan</option>`);
        }else{
            monthSelect.prop('disabled', false)
            menuActualDataField.prop('disabled', false)
            $.ajax({
                url : "{{url('actual-data-month')}}" + '/' + value,
                method : 'get',
                headers : xcsrf,
                dataType : 'json',
                success : function(data){
                    console.log(data)
                    timeSeriesData.push(data)
                    monthSelect.html('');
                    yearDataField.val('')
                    if(Array.isArray(data)){
                        $.each(data, function(key, value) {
                            monthSelect.append(`<option value="` + value.data.id + `"> ` + value.data.month +`  `+ value.year +` </option>`)
                        });
                        let last = data[data.length - 1];
                        yearDataField.val(data[0].year)
                    }else{
                        monthSelect.append(`<option value="` + data.id + `"> ` + data.month +`  `+ data.year +` </option>`)
                        yearDataField.val(data.year)
                    }
                }
            })
        }
    }

    function changeYearValue(){
        let selectedOption = $("#monthList option:selected" ).text();
        let splitTextSelected = selectedOption.split(" ")
        yearDataField.val(splitTextSelected[3])
    }

    // Submit Form
    function submitActualData(url){
        let formData = $('#menuFrom').serializeArray();
        let csrf_token = "{{ csrf_token() }}";
        let method = '';
        let urlMethod = '';
        
        if(url == 'create'){
            method = 'post';
            urlMethod = "{{ url('actual-data/store')}}";
        }else{
            method = 'patch';
            urlMethod = "{{ url('actual-data-update')}}" + '/' + actualIdField.val();
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
        $('#menuFrom')[0].reset();
        menuActualDataField.prop('disabled', true)
        monthSelect.prop('disabled', true)
        monthSelect.html(`<option>Pilih Bulan</option>`)
        yearDataField.val('')
    }

    // Retrieve data from db for update purpose
    function updateCategory(id){
        $('.modal-create').modal('show');
        $('.btn-back-properties').hide();
        showEditModal()
        let csrf_token = "{{csrf_token()}}"

        $.ajax({
            url : "{{url('actual-data')}}" + '/' + id,
            method : 'get',
            dataType : 'json',
            headers: {'X-CSRF-TOKEN': csrf_token},
            success: function(data){
                console.log(data)
                actualIdField.val(data.id)
                menuIdField.html('');
                menuIdField.prop('disabled', true);
                menuIdField.append(`<option value="` + data.menu.id + `"> ` + data.menu.menu_name + ` </option>`)
                menuActualDataField.prop('disabled', false)
                menuActualDataField.val(data.actual)
                yearDataField.val(data.year)
                monthSelect.html('');
                monthSelect.append(`<option value="` + data.month.id + `"> ` + data.month.month + ` </option>`)
            }

        })
    }

</script>
    {!! $dataTable->scripts() !!}
<script>
    filtermenu.on('change', function(){
        let dtable = $('#actualdatadatatabletable').DataTable()
        dtable.column(1).search($(this).val()).draw();
    })
</script>
@endpush
