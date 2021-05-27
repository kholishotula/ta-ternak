$.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


var segments = location.pathname.split('/');
var seg = segments[1];
var url_seg;
var formData;

if(seg == 'admin'){
    url_seg = "/admin";
}
else if(seg == 'peternak'){
    url_seg = "/peternak";
}
$('#eform-file').hide();

$('#tambah_data').on('click', function(){
    $('.modal-title').text('Tambah Data - Perkembangan');
    $('#action_button').val('Tambah');
    $('#action_button').addClass('btn-success');
    $('#action_button').removeClass('btn-warning');
    $('#action').val('Add');
    $('#form_result').html('');

    $('#tambah_data_form')[0].reset();
    $('#necktag').val('').change();
    $('#form-file').show();

    $('#formModal').modal('show');
});

$('#tambah_data_form').on('submit', function(event){
    event.preventDefault();
    var action_url = '';
    var method_form = '';
    
    //tambah
    if($('#action').val() == 'Add'){
        action_url = url_seg+"/perkembangan";
        method_form = "POST";
        formData = new FormData(this);

        $.ajax({
            url: action_url,
            method: method_form,
            data: formData,
            // data: $(this).serialize(),
            // datatype: "json",
            processData: false,
            contentType: false,
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
                    $('#perkembangan-table').DataTable().ajax.reload();
                }
                $('#form_result').html(html);
            },
            error: function (jqXHR, textStatus, errorThrown) { 
                console.log(jqXHR); 
            }
        });
    }

    //edit
    if($('#action').val() == 'Edit'){
        var updateId = $('#hidden_id').val();
        action_url = url_seg+"/perkembangan/"+updateId;
        method_form = "PUT";
        
        $.ajax({
            url: action_url,
            method: method_form,
            // data: formData,
            data: $(this).serialize(),
            datatype: "json",
            // processData: false,
            // contentType: false,
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
                    $('#perkembangan-table').DataTable().ajax.reload();
                }
                $('#form_result').html(html);
            },
            error: function (jqXHR, textStatus, errorThrown) { 
                console.log(jqXHR); 
            }
        });
    }
});

//view
$(document).on('click', '.view', function(){
    var id = $(this).attr('id');

    $.ajax({
        url: url_seg+"/perkembangan/"+id, //show
        datatype: "json",
        success: function(data){
            $('#vnecktag').val(data.result.necktag);
            $('#vtgl_perkembangan').val(data.result.tgl_perkembangan);
            $('#vberat_badan').val(data.result.berat_badan);
            $('#vpanjang_badan').val(data.result.panjang_badan);
            $('#vlingkar_dada').val(data.result.lingkar_dada);
            $('#vtinggi_pundak').val(data.result.tinggi_pundak);
            $('#vlingkar_skrotum').val(data.result.lingkar_skrotum);
            if(data.result.foto){
                $('#vform-file').show();
                $('#vimage').attr('src', segments[0] + '/' + data.result.foto).width(150);
            }
            else{
                $('#vform-file').hide();
            }
            $('#vketerangan').val(data.result.keterangan);
            $('#vcreated_at').val(data.result.created_at);
            $('#vupdated_at').val(data.result.updated_at);

            $('.modal-title').text('Data Perkembangan - ' + id);
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
        url: url_seg+"/perkembangan/"+id+"/edit",
        datatype: "json",
        success: function(data){
             // console.log(data);
            $('#necktag').val(data.result.necktag).change();
            $('#tgl_perkembangan').val(data.result.tgl_perkembangan);
            $('#berat_badan').val(data.result.berat_badan);
            $('#panjang_badan').val(data.result.panjang_badan);
            $('#lingkar_dada').val(data.result.lingkar_dada);
            $('#tinggi_pundak').val(data.result.tinggi_pundak);
            $('#lingkar_skrotum').val(data.result.lingkar_skrotum);
            // if(data.result.foto){
            //     $('#eform-file').show();
            //     $('#eimage').attr('src', segments[0] + '/' + data.result.foto).width(150);
                $('#form-file').hide();
            // }
            // else{
            //     $('#eform-file').hide();
            //     $('#form-file').show();
            // }
            // $('#image').val(data.image).change();
            $('#keterangan').val(data.result.keterangan);

            $('#hidden_id').val(id);
            $('#action').val('Edit');
            $('#action_button').val('Ubah');
            $('#action_button').addClass('btn-warning');
            $('#action_button').removeClass('btn-success');
            $('.modal-title').text('Edit Data - Perkembangan');
            $('#formModal').modal('show');
        },
        error: function (jqXHR, textStatus, errorThrown) { 
            console.log(jqXHR); 
        }
    });
});

$("#ubah-foto").change(function() {
    var val = $(this).val();
    if(val === "ya") {
        $('#form-file').show();
    }
});

//delete
$(document).on('click', '.delete', function(){
    var perkembangan_id = $(this).attr('id');

    swal({
        title: "Anda yakin ingin menghapus data perkembangan ternak ini?",
        text: "Data tidak dapat dikembalikan!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ya, hapus!",
        closeOnConfirm: false
    }, function(){
        $.ajax({
            url: url_seg+"/perkembangan/"+perkembangan_id,
            method: "DELETE",
            success: function(data){
                $('#perkembangan-table').DataTable().ajax.reload();
                swal("Terhapus!", "Data perkembangan id "+perkembangan_id+" telah terhapus.", "success");
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