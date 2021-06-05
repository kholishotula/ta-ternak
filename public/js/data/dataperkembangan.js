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
else if(seg == 'ketua-grup'){
    url_seg = "/ketua-grup";
}

$('#foto').change(function(){
    let reader = new FileReader();

    reader.onload = (e) => { 
      $('#preview-image').attr('src', e.target.result); 
    }

    reader.readAsDataURL(this.files[0]); 
});

$('#tambah_data').click(function () {
    $('#tambah_edit_data_form').trigger("reset");
    $('.modal-title').text("Tambah Data - Perkembangan");
    $('#formModal').modal('show');
    $('#necktag').val('');
    $('#hidden_id').val('');
    $('#preview-image').attr('src', 'https://www.riobeauty.co.uk/images/product_image_not_found.gif');
    $('#btn-save').text('Tambah');
    $('#btn-save').addClass('btn-success');
    $('#btn-save').removeClass('btn-warning');
});

$('#tambah_edit_data_form').on('submit', function(e) {

    e.preventDefault();
 
    var formData = new FormData(this);
    var url_form;
 
    if($('#btn-save').text() == 'Tambah'){
        url_form = url_seg + "/perkembangan";
    }
    else{
        var id = $('#hidden_id').val();
        url_form = url_seg + "/perkembangan/" + id;
        formData.append('_method', 'PUT');
    }
    $.ajax({
        type: 'POST',
        url: url_form,
        data: formData,
        contentType: false,
        processData: false,
        success: (data) => {
            // $("#formModal").modal('hide');

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
                $('#tambah_edit_data_form')[0].reset();
                $('#perkembangan-table').DataTable().ajax.reload();
            }
            $('#form_result').html(html);
       },
       error: function(jqXHR, textStatus, errorThrown){
          console.log(jqXHR);
        }
      });
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
            $('#necktag').val(data.result.necktag).change();
            $('#tgl_perkembangan').val(data.result.tgl_perkembangan);
            $('#berat_badan').val(data.result.berat_badan);
            $('#panjang_badan').val(data.result.panjang_badan);
            $('#lingkar_dada').val(data.result.lingkar_dada);
            $('#tinggi_pundak').val(data.result.tinggi_pundak);
            $('#lingkar_skrotum').val(data.result.lingkar_skrotum);
            if(data.result.foto != null){
                $('#preview-image').attr('src', segments[0] + '/' + data.result.foto).width(150); 
            }
            else{
                $('#preview-image').attr('src', 'https://www.riobeauty.co.uk/images/product_image_not_found.gif');
            }
            $('#keterangan').val(data.result.keterangan);

            $('#hidden_id').val(id);
            $('#btn-save').text('Ubah');
            $('#btn-save').addClass('btn-warning');
            $('#btn-save').removeClass('btn-success');
            $('.modal-title').text('Edit Data - Perkembangan');
            $('#formModal').modal('show');
        },
        error: function (jqXHR, textStatus, errorThrown) { 
            console.log(jqXHR); 
        }
    });
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