$.ajaxSetup({
	headers: {
	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
});

var segments = location.pathname.split('/');

$('select[name="provinsi"]').on('change', function() {
    var prov_id = $(this).val();
    if(prov_id) {
        $.ajax({
            url: segments[0]+'/wilayah/kabupaten/'+prov_id.split('-')[0],
            type: "GET",
            dataType: "json",
            success:function(data) {
                $('select[name="kab_kota"]').empty();

                $.each(data.kab, function(key, value) {
                    $('select[name="kab_kota"]').append('<option value="'+ value['id'] + '-' + value['name'] +'">'+ value['name'] +'</option>');
                });
            }
        });
    }else{
        $('select[name="kab_kota"]').empty();
    }
});

$('select[name="kab_kota"]').on('change', function() {
    var kab_id = $(this).val();
    if(kab_id) {
        $.ajax({
            url: segments[0]+'/wilayah/kecamatan/'+kab_id.split('-')[0],
            type: "GET",
            dataType: "json",
            success:function(data) {
                $('select[name="kecamatan"]').empty();

                $.each(data.kec, function(key, value) {
                    $('select[name="kecamatan"]').append('<option value="'+ value['id'] + '-' + value['name'] +'">'+ value['name'] +'</option>');
                });
            }
        });
    }else{
        $('select[name="kecamatan"]').empty();
    }
});

$('#tambah_data').click(function(){
    $('.modal-title').text('Tambah Data - Grup Peternak');
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
        action_url = "/admin/grup-peternak";
        method_form = "POST";
    }

    //edit
    if($('#action').val() == 'Edit'){
        var updateId = $('#hidden_id').val();
        action_url = "/admin/grup-peternak/"+updateId;
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
                $('#form_result').html(html);
			}
			if (data.success) {
            	$('#formModal').modal('hide');
                swal({
                    title: "Berhasil!",
                    type: "success",
                    text: data.success,
                });
				$('#tambah_data_form')[0].reset();
				$('#grup-peternak-table').DataTable().ajax.reload();
			}
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
        url: "/admin/grup-peternak/"+id+"/edit",
        datatype: "json",
        success: function(data){
            $('#nama_grup').val(data.result.nama_grup);
            $('#alamat').val(data.result.alamat);
            $('#provinsi').val(data.result.provinsi).change();
            $('#kab_kota').val(data.result.kab_kota).change();
            $('#kecamatan').val(data.result.kecamatan).change();
            $('#keterangan').val(data.result.keterangan);
            $('#hidden_id').val(id);
            $('#action').val('Edit');
            $('#action_button').val('Ubah');
            $('#action_button').addClass('btn-warning');
            $('#action_button').removeClass('btn-success');
            $('.modal-title').text('Edit Data - Grup Peternak');
            $('#formModal').modal('show');
        }
    });
});

//delete
$(document).on('click', '.delete', function(){
    var grup_id = $(this).attr('id');

    swal({
        title: "Anda yakin ingin menghapus data grup peternak ini?",
        text: "Data tidak dapat dikembalikan!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ya, hapus!",
        closeOnConfirm: false
    }, function(){
        $.ajax({
            url:"/admin/grup-peternak/"+grup_id,
            method: "DELETE",
            success: function(data){
                if (data.error) {
                    swal({
                        title: 'Opps...',
                        text : 'Data grup peternak id ' + grup_id + ' tidak dapat dihapus.',
                        type : 'error'
                    })
                }
                else{
                    $('#grup-peternak-table').DataTable().ajax.reload();
                    swal("Terhapus!", "Data grup peternak id "+grup_id+" telah terhapus.", "success");
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