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
	$('.modal-title').text('Tambah Data - Penyakit');
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
		action_url = url_seg+"/penyakit";
		method_form = "POST";
	}

	//edit
	if($('#action').val() == 'Edit'){
		var updateId = $('#hidden_id').val();
		action_url = url_seg+"/penyakit/"+updateId;
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
				$('#penyakit-table').DataTable().ajax.reload();
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
    txt += '<th>Tanggal Sakit</th>';
    txt += '<th>Obat</th>';
    txt += '<th>Lama Sakit</th>';
    txt += '<th>Keterangan</th>';
    txt += '</tr>';

    $.ajax({
        url: url_seg+"/penyakit/"+id, //show
        datatype: "json",
        success: function(data){
            $('#vnama_penyakit').val(data.result.nama_penyakit);
            $('#vketerangan').val(data.result.keterangan);
            $('#vcreated_at').val(data.result.created_at);
            $('#vupdated_at').val(data.result.updated_at);

            if(data.riwayat != ''){
                $('#riwayat-penyakit').empty().append(txt);
                $.each(data.riwayat, function(i, val) {
                    var rp1 = data.riwayat[i].rp_penyakit.split('(');
                    var rp2 = rp1[1].split(')');
                    rp[i] = rp2[0].split(',');
                    //1: nama penyakit, 2: date, 3: obat, 4: lama sakit, 5: ket

                    txt2 = '<tr>'; 
                    for(var j = 1; j <= 5; j++){ 
                        if(rp[i][j-1] == ""){
                            rp[i][j-1] = '-';
                        } 
                        txt2 += '<td>' + rp[i][j-1] + '</td>';
                    }
                    txt2 += '</tr>';
                    $('#riwayat-penyakit').append(txt2);
                    $('#riwayat-penyakit').show();
                });
                $('#span-rp').empty();
            }
            else{
                $('#span-rp').html('<p align="center">Tidak ada data riwayat penyakit ini pada ternak</p>');
                $('#riwayat-penyakit').hide();
            }

            $('.modal-title').text('Data Penyakit - '+id);
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
		url: url_seg+"/penyakit/"+id+"/edit",
		datatype: "json",
		success: function(data){
			$('#nama_penyakit').val(data.result.nama_penyakit);
			$('#ket_penyakit').val(data.result.ket_penyakit);
			$('#hidden_id').val(id);
	    	$('#action').val('Edit');
			$('#action_button').val('Ubah');
            $('#action_button').addClass('btn-warning');
            $('#action_button').removeClass('btn-success');
			$('.modal-title').text('Edit Data - Penyakit');
	    	$('#formModal').modal('show');
		}
	});
});

//delete
$(document).on('click', '.delete', function(){
	var penyakit_id = $(this).attr('id');
	
    swal({
        title: "Anda yakin ingin menghapus data penyakit ini?",
        text: "Data tidak dapat dikembalikan!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ya, hapus!",
        closeOnConfirm: false
    }, function(){
        $.ajax({
            url: url_seg+"/penyakit/"+penyakit_id,
            method: "DELETE",
            success: function(data){
                if (data.error) {
                    swal({
                        title: 'Opps...',
                        text : 'Data penyakit id ' + penyakit_id + ' tidak dapat dihapus.',
                        type : 'error'
                    })
                }
                else{
                    $('#penyakit-table').DataTable().ajax.reload();
                    swal("Terhapus!", "Data penyakit id "+penyakit_id+" telah terhapus.", "success");
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