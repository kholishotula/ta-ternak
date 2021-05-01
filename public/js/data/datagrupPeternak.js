$.ajaxSetup({
	headers: {
	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
            console.log(data);
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
				$('#grup-peternak-table').DataTable().ajax.reload();
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
        url: "/admin/grup-peternak/"+id+"/edit",
        datatype: "json",
        success: function(data){
            $('#nama_grup').val(data.result.nama_grup);
            $('#alamat').val(data.result.alamat);
            $('#provinsi').val(data.result.provinsi);
            $('#kab_kota').val(data.result.kab_kota);
            $('#kecamatan').val(data.result.kecamatan);
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