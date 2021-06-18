// const { method } = require("lodash");

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

function tongsampahDT() {
    if (!$.fn.dataTable.isDataTable('#tongsampah-table')) {
		$('#tongsampah-table').DataTable({
		    processing: true,
		    serverSide: true,
		    ajax: url_seg+'/ternaktrash',
		    columns: [
		        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
		        {data: 'necktag', name: 'necktag'},
		        {data: 'pemilik_id', name: 'pemilik_id'},
                {data: 'user_id', name: 'peternak_id'},
		        {data: 'ras_id', name: 'ras_id'},
		        {data: 'jenis_kelamin', name: 'jenis_kelamin'},
		        {data: 'necktag_ayah', name: 'necktag_ayah'},
		        {data: 'necktag_ibu', name: 'necktag_ibu'},
		        {data: 'status_ada', name: 'status_ada'},
		        {data: 'created_at', name: 'created_at'},
		        {data: 'updated_at', name: 'updated_at'},
		        {data: 'deleted_at', name: 'deleted_at'},
		        {data: 'action', name: 'action', orderable: false, searchable: false, class: 'text-center'},
		    ],
		});
    }
    else{
        $('#tongsampah-table').DataTable().ajax.reload();
    }
}

$('#tambah_data').click(function(){
	$('.modal-title').text('Tambah Data - Ternak');
	$('#action_button').val('Tambah');
	$('#action_button').addClass('btn-success');
	$('#action_button').removeClass('btn-warning');
	$('#action').val('Add');
	$('#action').show();
	$('#action_button').show();
	$('#form_result').html('');
	
	$('#kematian_form').hide();
	$('#necktag_form').hide();

    $('#tambah_data_form')[0].reset();
    $('#necktag').val('').change();
    $('#pemilik_id').val('').change();
    $('#peternak_id').val('').change();
    $('#ras_id').val('').change();
    $('#kematian_id').val('').change();
    $('#necktag_ayah').val('').change();
    $('#necktag_ibu').val('').change();

	$('#formModal').modal('show');
});

$('#tambah_data_form').on('submit', function(event){
	event.preventDefault();
	var action_url = '';
	var method_form = '';

	//tambah
	if($('#action').val() == 'Add'){
		action_url = url_seg+"/ternak";
		method_form = "POST";
	}

	//edit
	if($('#action').val() == 'Edit'){
		var updateId = $('#hidden_id').val();
		action_url = url_seg+"/ternak/"+updateId;
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
			if (data.error) {
				html = '<div class="alert alert-danger">' + data.error + '</div>';
			}
			if (data.success) {
				$('#formModal').modal('hide');
                swal({
                    title: "Berhasil!",
                    type: "success",
                    text: data.success,
                });
				$('#tambah_data_form')[0].reset();
				$('#ternak-table').DataTable().ajax.reload();
			}
			$('#form_result').html(html);
		},
		error: function (jqXHR, textStatus, errorThrown) { 
			console.log(jqXHR); 
		}
	});
});

//view
$(document).on('click', '.view', function(){
	var id = $(this).attr('id');
    var txt = '', txt2 = '';

    txt = '<tr>';
    txt += '<th>Nama Penyakit</th>';
    txt += '<th>Tanggal Sakit</th>';
    txt += '<th>Obat</th>';
    txt += '<th>Lama Sakit</th>';
    txt += '<th>Keterangan</th>';
    txt += '</tr>';

	$('#form_result').html('');
	$.ajax({
		url: url_seg+"/ternak/"+id, //show
		datatype: "json",
		success: function(data){
			$('#vpemilik_id').val(data.result.pemilik_id);
            $('#vpeternak_id').val(data.result.user_id);
			$('#vras_id').val(data.result.ras_id);
			$('#vkematian_id').val(data.result.kematian_id);
			$('#vjenis_kelamin').val(data.result.jenis_kelamin);
			$('#vtgl_lahir').val(data.result.tgl_lahir);
			$('#vbobot_lahir').val(data.result.bobot_lahir);
			$('#vpukul_lahir').val(data.result.pukul_lahir);
			$('#vlama_dikandungan').val(data.result.lama_dikandungan);
			$('#vlama_laktasi').val(data.result.lama_laktasi);
			$('#vtgl_lepas_sapih').val(data.result.tgl_lepas_sapih);
			$('#vblood').val(data.result.blood);
			$('#vnecktag_ayah').val(data.result.necktag_ayah).change();
			$('#vnecktag_ibu').val(data.result.necktag_ibu).change();
			$('#vbobot_tubuh').val(data.result.bobot_tubuh);
			$('#vpanjang_tubuh').val(data.result.panjang_tubuh);
			$('#vtinggi_tubuh').val(data.result.tinggi_tubuh);
			$('#vcacat_fisik').val(data.result.cacat_fisik);
			$('#vciri_lain').val(data.result.ciri_lain);
			$('#vstatus_ada').val(data.result.status_ada);
			$('#vcreated_at').val(data.result.created_at);
			$('#vupdated_at').val(data.result.updated_at);

            if(data.riwayat != null){
                $('#riwayat-penyakit').empty().append(txt);
                $.each(data.riwayat, function(i, val) {
                    //1: nama penyakit, 2: date, 3: obat, 4: lama sakit, 5: ket
                    
                    txt2 = '<tr>'; 
                    txt2 += '<td>' + data.riwayat[i].nama_penyakit + '</td>';
                    txt2 += '<td>' + data.riwayat[i].tgl_sakit + '</td>';
                    txt2 += '<td>' + data.riwayat[i].obat + '</td>';
                    txt2 += '<td>' + data.riwayat[i].lama_sakit + '</td>';
                    txt2 += '<td>' + data.riwayat[i].keterangan + '</td>';
                    txt2 += '</tr>';
                    $('#riwayat-penyakit').append(txt2);
                    $('#riwayat-penyakit').show();
                });
                $('#span-rp').empty();
            }
            else{
                $('#span-rp').html('<p align="center">Tidak ada data riwayat penyakit</p>');
                $('#riwayat-penyakit').hide();
            }

			$('.modal-title').text('Data Ternak - '+id);
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
		url: url_seg+"/ternak/"+id+"/edit", //edit
		datatype: "json",
		success: function(data){
			$('#necktag').val(data.result.necktag);
			$('#pemilik_id').val(data.result.pemilik_id).change();
            $('#peternak_id').val(data.result.user_id).change();
            $('#ras_id').val(data.result.ras_id).change();
			$('#jenis_kelamin').val(data.result.jenis_kelamin);
			$('#tgl_lahir').val(data.result.tgl_lahir);
			$('#bobot_lahir').val(data.result.bobot_lahir);
			$('#pukul_lahir').val(data.result.pukul_lahir);
			$('#lama_dikandungan').val(data.result.lama_dikandungan);
			$('#lama_laktasi').val(data.result.lama_laktasi);
			$('#tgl_lepas_sapih').val(data.result.tgl_lepas_sapih);
			$('#necktag_ayah').val(data.result.necktag_ayah).change();
			$('#necktag_ibu').val(data.result.necktag_ibu).change();
			$('#cacat_fisik').val(data.result.cacat_fisik);
			$('#ciri_lain').val(data.result.ciri_lain);
			$('#status_ada').val(data.result.status_ada);

            $('#kematian_form').show();
            $('#necktag_form').show();
			$('#necktag').attr('readonly', true);
			$('#hidden_id').val(id);
	    	$('#action').val('Edit');
			$('#action_button').val('Ubah');
			$('#action_button').addClass('btn-warning');
			$('#action_button').removeClass('btn-success');
			$('.modal-title').text('Edit Data - Ternak');
	    	$('#formModal').modal('show');
		},
        error: function (jqXHR, textStatus, errorThrown) { 
            console.log(jqXHR); 
        }
	});
});

//delete
$(document).on('click', '.delete', function(){
	var ternak_id = $(this).attr('id');
	
    swal({
        title: "Anda yakin ingin menghapus data ternak ini?",
        text: "Data mungkin masih digunakan pada tabel lain!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ya, hapus!",
        closeOnConfirm: false
    }, function(){
        $.ajax({
            url: url_seg+"/ternak/"+ternak_id,
            method: "DELETE",
            success: function(data){
                if (data.error) {
                    swal({
                        title: 'Opps...',
                        text : 'Data ternak id ' + ternak_id + ' tidak dapat dihapus.',
                        type : 'error'
                    })
                }
                else{
                    $('#ternak-table').DataTable().ajax.reload();
                    swal("Terhapus!", "Data ternak id " + ternak_id + " berada di tong sampah.", "success");
                }
            },
            error: function(data){
                swal({
                    title: 'Opps...',
                    text : 'Error',
                    type : 'error'
                })
            }
        });
    });

});



// -------------------------------- tong sampah ------------------------------------------

// restore
$(document).on('click', '.restore', function(){
	var val = $(this).attr('id');

	swal({
        title: "Anda yakin ingin mengembalikan data ternak ini?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ya, kembalikan!",
        closeOnConfirm: false
    }, function(){
        $.ajax({
            url: url_seg+"/ternak/restore/"+val,
            method: "GET",
            success: function(data){
                $('#ternak-table').DataTable().ajax.reload();
                $('#tongsampah-table').DataTable().ajax.reload();
                swal("Berhasil!", "Data ternak id " + val + " berhasil dikembalikan.", "success");
            },
            error: function(data){
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

// restore all
$('#btn-restore-all').click(function(){
	swal({
        title: "Anda yakin ingin mengembalikan semua data ternak di tong sampah?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ya, kembalikan!",
        closeOnConfirm: false
    }, function(){
        $.ajax({
            url: url_seg+"/ternakrestore",
            method: "GET",
            success: function(data){
                $('#ternak-table').DataTable().ajax.reload();
                $('#tongsampah-table').DataTable().ajax.reload();
                swal("Berhasil!", "Data ternak berhasil dikembalikan.", "success");
            },
            error: function(data){
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


// force delete
$(document).on('click', '.fdelete', function(){
	var val = $(this).attr('id');

	swal({
        title: "Anda yakin ingin menghapus permanen data ternak ini?",
        text: "Data tidak dapat dikembalikan!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ya, hapus!",
        closeOnConfirm: false
    }, function(){
        $.ajax({
            url: url_seg+"/ternak/fdelete/"+val,
            method: "DELETE",
            success: function(data){
                $('#tongsampah-table').DataTable().ajax.reload();
                swal("Terhapus!", "Data ternak id " + val + " berhasil di hapus.", "success");
            },
            error: function(data){
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

// force delete all
$('#btn-delete-all').click(function(){
	swal({
        title: "Anda yakin ingin menghapus permanen semua data ternak di tong sampah?",
        text: "Data tidak dapat dikembalikan!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ya, hapus!",
        closeOnConfirm: false
    }, function(){
        $.ajax({
            url: url_seg+"/ternakfdelete",
            method: "DELETE",
            success: function(data){
                $('#tongsampah-table').DataTable().ajax.reload();
                swal("Terhapus!", "Data ternak berhasil di hapus.", "success");
            },
            error: function(data){
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
