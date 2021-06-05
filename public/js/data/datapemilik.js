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
else if(seg == 'ketua-grup'){
    url_seg = "/ketua-grup";
}

$('#tambah_data').click(function(){
    $('.modal-title').text('Tambah Data - Pemilik');
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
        action_url = url_seg+"/pemilik";
        method_form = "POST";
    }

    //edit
    if($('#action').val() == 'Edit'){
        var updateId = $('#hidden_id').val();
        action_url = url_seg+"/pemilik/"+updateId;
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
                $('#pemilik-table').DataTable().ajax.reload();
            }
            $('#form_result').html(html);
        }
    });
});

//view
$(document).on('click', '.view', function(){
    var id = $(this).attr('id');
    var txt = '', txt2 = '';
    var rp = [];

    txt = '<tr>';
    txt += '<th>Necktag</th>';
    txt += '<th>Jenis Kelamin</th>';
    txt += '<th>Status Ada</th>';
    txt += '</tr>';

    $.ajax({
        url: url_seg+"/pemilik/"+id, //show
        datatype: "json",
        success: function(data){
            $('#vnama_pemilik').val(data.result.nama_pemilik);
            $('#vktp_pemilik').val(data.result.ktp_pemilik);
            $('#vcreated_at').val(data.result.created_at);
            $('#vupdated_at').val(data.result.updated_at);

            if(data.ternak != ''){
                console.log(data.ternak);
                $('#ternak-pemilik').empty().append(txt);
                txt2 = '';
                $.each(data.ternak, function(i, val) {
                    txt2 += '<tr>';
                    txt2 += '<td>' + data.ternak[i].necktag + '</td>';
                    txt2 += '<td>' + data.ternak[i].jenis_kelamin + '</td>';

                    if(data.ternak[i].status_ada) data.ternak[i].status_ada = 'Ada';
                    else data.ternak[i].status_ada = 'Tidak Ada';
                    
                    txt2 += '<td>' + data.ternak[i].status_ada + '</td>';
                    txt2 += '</tr>';
                });
                $('#ternak-pemilik').append(txt2);
                $('#ternak-pemilik').show();
                $('#span-rp').empty();
            }
            else{
                $('#span-rp').html('<p align="center">Tidak ada data ternak pada pemilik ini</p>');
                $('#ternak-pemilik').hide();
            }

            $('.modal-title').text('Data Pemilik - ' + id);
            $('#viewModal').modal('show');
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
        url: url_seg+"/pemilik/"+id+"/edit",
        datatype: "json",
        success: function(data){
            $('#nama_pemilik').val(data.result.nama_pemilik);
            $('#ktp_pemilik').val(data.result.ktp_pemilik);
            $('#hidden_id').val(id);
            $('#action').val('Edit');
            $('#action_button').val('Ubah');
            $('#action_button').addClass('btn-warning');
            $('#action_button').removeClass('btn-success');
            $('.modal-title').text('Edit Data - Pemilik');
            $('#formModal').modal('show');
        }
    });
});

//delete
$(document).on('click', '.delete', function(){
    var pemilik_id = $(this).attr('id');

    swal({
        title: "Anda yakin ingin menghapus data pemilik ini?",
        text: "Data tidak dapat dikembalikan!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ya, hapus!",
        closeOnConfirm: false
    }, function(){
        $.ajax({
            url: url_seg+"/pemilik/"+pemilik_id,
            method: "DELETE",
            success: function(data){
                if (data.error) {
                    swal({
                        title: 'Opps...',
                        text : 'Data pemilik id ' + pemilik_id + ' tidak dapat dihapus.',
                        type : 'error'
                    })
                }
                else{
                    $('#pemilik-table').DataTable().ajax.reload();
                    swal("Terhapus!", "Data pemilik id "+pemilik_id+" telah terhapus.", "success");
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