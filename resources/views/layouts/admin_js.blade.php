<script>
    let model_id = [];

    let tableId = $('table').prop('id');

    initMassSelection(tableId)

    function initMassSelection(tableId){
        $('#'+tableId).on('draw.dt', function(){
            $('input[type="checkbox"]').iCheck({
                checkboxClass: 'icheckbox_flat-orange',
                radioClass: 'iradio_flat-orange',
                increaseArea: '50%' // optional
            });

            $('input[type="checkbox"]').on('ifChecked', function (event) { // If single checkbox is checked
                getModelId()
            })

            $('input[type="checkbox"]').on('ifUnchecked', function (event) { // If single checkbox is unchecked
                getModelId()
            })

            $('#check_all').on('ifChecked', function (event) { // If select all checkbox is checked
                getModelId()
                let checkbox = $('input[type="checkbox"]');
                checkbox.iCheck('check');
            })
            $('#check_all').on('ifUnchecked', function (event) { // If select all checkbox is unchecked
                getModelId()
                let checkbox = $('input[type="checkbox"]');
                checkbox.iCheck('uncheck');
            })
        });
    }

    function getModelId(){
        // Reset user id array back to 0. If not the function bellow keep append new array to user id
        model_id.length = 0

        // Push new array to notice_id
        $.each($("input[type='checkbox'].checkbox_id:checked"), function(){
            model_id.push($(this).val())
        });
    }

    function deleteSelection(deleteUrl){
        if(typeof model_id !== 'undefined' && model_id.length == 0){ // Check if no data selected, and return error
            Swal.fire({
                title: 'Warning',
                text: "No data selected",
                icon: 'warning',
                confirmButtonColor: '#169b6b',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ok'
            })
        }else{
            Swal.fire({
                title: '{{trans("alert.alert_delete_prompt")}}',
                text: "{!!trans('alert.alert_delete_warning')!!}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#169b6b',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{trans("alert.alert_delete_confirm")}}'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : deleteUrl + '/' + model_id,
                        dataType : "Json",
                        data : {"_token": "{{ csrf_token() }}", model_id},
                        method : "get",
                        success:function(data){
                            // console.log(data)
                            model_id.length = 0 // Reset notice_id array back to 0
                            if(data[0] == 1){
                                $('#check_all').iCheck('uncheck')
                                Swal.fire(
                                    '{{trans("alert.alert_success")}}',
                                    data[1],
                                    'success'
                            )
                            }else{
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
            })
        }
    }

    function deleteSingle(id, deleteUrl){
        Swal.fire({
            title: '{{trans("alert.alert_delete_prompt")}}',
            text: "{!!trans('alert.alert_delete_warning')!!}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#169b6b',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{trans("alert.alert_delete_confirm")}}'
        }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                    url : deleteUrl + '/' + id,
                    dataType : "Json",
                    data : {"_token": "{{ csrf_token() }}"},
                    method : "get",
                    success:function(data){
                        console.log(data)
                        if(data[0] == 1){
                        $('#check_all').iCheck('uncheck')
                        Swal.fire(
                            '{{trans("alert.alert_success")}}',
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
        })
    }


</script>