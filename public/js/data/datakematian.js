$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


var segments = location.pathname.split('/');
var seg = segments[1];
var url_seg;

if(seg == 'admin'){
    url_seg = "/admin";
}
else if(seg == 'peternak'){
    url_seg = "/peternak";
}


$('#tambah_data').click(function(){
    $('.modal-title').text('Tambah Data - Ternak Mati');
    $('#action_button').val('Tambah');
    $('#action_button').addClass('btn-success');
    $('#action_button').removeClass('btn-warning');
    $('#action').val('Add');
    $('#form_result').html('');
    $('#tambah_data_form')[0].reset();
    $('#formModal').modal('show');
});

$('#tambah_data_form').on('submit', function(event){
    event.preventDefault();
    var action_url = '';
    var method_form = '';

    //tambah
    if($('#action').val() == 'Add'){
        action_url = url_seg+"/kematian";
        method_form = "POST";
    }

    //edit
    if($('#action').val() == 'Edit'){
        var updateId = $('#hidden_id').val();
        action_url = url_seg+"/kematian/"+updateId;
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
                $('#kematian-table').DataTable().ajax.reload();
            }
            $('#form_result').html(html);
        },
        error: function (jqXHR, textStatus, errorThrown) { 
            console.log(jqXHR); 
        }
    });
});

//edit
$(document).on('click', '.edit', function(){
    var id = $(this).attr('id');
    $('#form_result').html('');
    $.ajax({
        url: url_seg+"/kematian/"+id+"/edit",
        datatype: "json",
        success: function(data){
            $('#tgl_kematian').val(data.result.tgl_kematian);
            $('#waktu_kematian').val(data.result.waktu_kematian);
            $('#penyebab').val(data.result.penyebab);
            $('#kondisi').val(data.result.kondisi);
            $('#hidden_id').val(id);
            $('#action').val('Edit');
            $('#action_button').val('Ubah');
            $('#action_button').addClass('btn-warning');
            $('#action_button').removeClass('btn-success');
            $('.modal-title').text('Edit Data - Ternak Mati');
            $('#formModal').modal('show');
        }
    });
});

//delete
$(document).on('click', '.delete', function(){
    var kematian_id = $(this).attr('id');

    swal({
        title: "Anda yakin ingin menghapus data kematian ini?",
        text: "Data tidak dapat dikembalikan!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ya, hapus!",
        closeOnConfirm: false
    }, function(){
        $.ajax({
            url: url_seg+"/kematian/"+kematian_id,
            method: "DELETE",
            success: function(data){
                if (data.error) {
                    swal({
                        title: 'Opps...',
                        text : 'Data kematian id ' + kematian_id + ' tidak dapat dihapus.',
                        type : 'error'
                    })
                }
                else{
                    $('#kematian-table').DataTable().ajax.reload();
                    swal("Terhapus!", "Data kematian id "+kematian_id+" telah terhapus.", "success");
                }
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