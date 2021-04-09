$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('#tambah_data').click(function(){
    $('.modal-title').text('Tambah Data - Peternak');
    $('#action_button').val('Tambah');
    $('#action_button').addClass('btn-success');
    $('#action_button').removeClass('btn-warning');
    $('#action').val('Add');
    $('#form_result').html('');
    $('#tambah_data_form')[0].reset();
    $('#register').show();
    // $('.reg-admin').hide();

    $('#peternakan_id').val('').change();
    $('#formModal').modal('show');
});

$('#tambah_data_form').on('submit', function(event){
    event.preventDefault();
    var action_url = '';
    var method_form = '';

    //tambah
    if($('#action').val() == 'Add'){
        action_url = "/admin/peternak";
        method_form = "POST";
    }

    //edit
    if($('#action').val() == 'Edit'){
        var updateId = $('#hidden_id').val();
        action_url = "/admin/peternak/"+updateId;
        method_form = "PUT";
    }

    $.ajax({
        url: action_url,
        method: method_form,
        data: $(this).serialize(),
        datatype: "json",
        success: function(data){
            var html = '';
            if (data.errors) {
                html = '<div class="alert alert-danger">';
                for (var count = 0; count < data.errors.length; count++) {
                    html += '<p>' + data.errors[count] + '</p>';
                }
                html += '</div>';
            }
            if (data.success) {
                html = '<div class="alert alert-success">' + data.success + '</div>';
                $('#tambah_data_form')[0].reset();
                $('#peternak-table').DataTable().ajax.reload();
            }
            $('#form_result').html(html);
        }
    });
});

//edit
$(document).on('click', '.edit', function(){
    var id = $(this).attr('id');
    $('#form_result').html('');
    $.ajax({
        url: "/admin/peternak/"+id+"/edit",
        datatype: "json",
        success: function(data){
            $('#peternakan_id').val(data.result.peternakan_id).change();
            $('#name').val(data.result.name);
            // $('.reg-admin').show();
            // $('#register_from_admin').val(data.result.register_from_admin).change();

            // $('#username').val(data.result.username);
            // $('#password_first').val(data.result.password_first);
            $('#hidden_id').val(id);
            $('#action').val('Edit');
            $('#action_button').val('Ubah');
            $('#action_button').addClass('btn-warning');
            $('#action_button').removeClass('btn-success');
            $('.modal-title').text('Edit Data - Peternak');

            $('#register').hide();
            $('#formModal').modal('show');
        }
    });
});

//delete
$(document).on('click', '.delete', function(){
    var peternak_id = $(this).attr('id');

    swal({
        title: "Anda yakin ingin menghapus data peternak ini?",
        text: "Data tidak dapat dikembalikan!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ya, hapus!",
        closeOnConfirm: false
    }, function(){
        $.ajax({
            url:"/admin/peternak/"+peternak_id,
            method: "DELETE",
            success: function(data){
                $('#peternak-table').DataTable().ajax.reload();
                swal("Terhapus!", "Data peternak id "+peternak_id+" telah terhapus.", "success");
            },
            error : function(){
                swal({
                    title: 'Opps...',
                    text : data.message,
                    type : 'error',
                    timer : '1500'
                })
            }
        });
    });

});
