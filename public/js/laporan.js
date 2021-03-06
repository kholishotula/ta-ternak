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

$('#laporan-hasil').hide();

var begin, finish;
var from, to, grup_id = null, nama_grup = null;

$('#reportrange').daterangepicker({
    opens: 'left'
}, function(start, end, label) {
    begin = start.format('YYYY-MM-DD');
    finish = end.format('YYYY-MM-DD');
});

$('#filter-form').on('submit', function(event){
    $('#laporan-hasil').hide();
	
    event.preventDefault();
        $.ajax({
            url: url_seg+"/laporan",
            method: "GET", 
            data: {
                // data: $(this).serialize,
                grup_id: $(this).serialize().split('&')[0].split('=')[1],
                datefrom: begin, 
                dateto: finish
            },
            dataType: "json",
            success:function(data) {
                from = begin;
                to = finish;
                grup_id = data.grup_id;
                $('#date-span').html('tanggal ' + from + ' sampai ' + to);
                if(data.nama_grup != null){
                    $('#grup-name').html(' untuk Grup Peternak "' + data.nama_grup + '"');
                }
                else{
                    $('#grup-name').html('');
                }

                $('#lahir-table').DataTable().ajax.reload();
                $('#mati-table').DataTable().ajax.reload();
                $('#jual-table').DataTable().ajax.reload();
                $('#kawin-table').DataTable().ajax.reload();
                $('#sakit-table').DataTable().ajax.reload();
                $('#perkembangan-table').DataTable().ajax.reload();
                $('#ada-table').DataTable().ajax.reload();

                $('#laporan-hasil').show();
            },
            error: function (jqXHR, textStatus, errorThrown) { 
                console.log(jqXHR); 
            }
        });
});

$('#lahir-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: url_seg+'/laporan/lahir',
        method: 'POST',
        data: function(d){
            d.datefrom = from;
            d.dateto = to;
            d.grup_id = grup_id;
        },
        error: function (jqXHR, textStatus, errorThrown) { 
            console.log(jqXHR); 
        }
    },
    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
        {data: 'necktag', name: 'necktag'},
        {data: 'pemilik_id', name: 'pemilik_id'},
        {data: 'user_id', name: 'peternak_id'},
        {data: 'ras_id', name: 'ras_id'},
        {data: 'kematian_id', name: 'kematian_id'},
        {data: 'penjualan_id', name: 'penjualan_id'},
        {data: 'jenis_kelamin', name: 'jenis_kelamin'},
        {data: 'tgl_lahir', name: 'tgl_lahir'},
        {data: 'bobot_lahir', name: 'bobot_lahir'},
        {data: 'pukul_lahir', name: 'pukul_lahir'},
        {data: 'lama_dikandungan', name: 'lama_dikandungan'},
        {data: 'lama_laktasi', name: 'lama_laktasi'},
        {data: 'tgl_lepas_sapih', name: 'tgl_lepas_sapih'},
        {data: 'necktag_ayah', name: 'necktag_ayah'},
        {data: 'necktag_ibu', name: 'necktag_ibu'},
        {data: 'cacat_fisik', name: 'cacat_fisik'},
        {data: 'ciri_lain', name: 'ciri_lain'},
        {data: 'status_ada', name: 'status_ada'},
        {data: 'created_at', name: 'created_at'},
        {data: 'updated_at', name: 'updated_at'},
    ],
});

$('#mati-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: url_seg+'/laporan/mati',
        method: 'POST',
        data: function(d){
            d.datefrom = from;
            d.dateto = to;
            d.grup_id = grup_id;
        },
        error: function (jqXHR, textStatus, errorThrown) { 
            console.log(jqXHR); 
        }
    },
    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
        {data: 'necktag', name: 'necktag'},
        {data: 'kematian_id', name: 'kematian_id'},
        {data: 'tgl_kematian', name: 'tgl_kematian'},
        {data: 'waktu_kematian', name: 'waktu_kematian'},
        {data: 'penyebab', name: 'penyebab'},
        {data: 'kondisi', name: 'kondisi'},
        {data: 'pemilik_id', name: 'pemilik_id'},
        {data: 'user_id', name: 'peternak_id'},
        {data: 'ras_id', name: 'ras_id'},
        {data: 'jenis_kelamin', name: 'jenis_kelamin'},
        {data: 'tgl_lahir', name: 'tgl_lahir'},
        {data: 'necktag_ayah', name: 'necktag_ayah'},
        {data: 'necktag_ibu', name: 'necktag_ibu'},
        {data: 'cacat_fisik', name: 'cacat_fisik'},
        {data: 'ciri_lain', name: 'ciri_lain'},
        {data: 'status_ada', name: 'status_ada'},
        {data: 'created_at', name: 'created_at'},
        {data: 'updated_at', name: 'updated_at'},
    ],
});

$('#jual-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: url_seg+'/laporan/jual',
        method: 'POST',
        data: function(d){
            d.datefrom = from;
            d.dateto = to;
            d.grup_id = grup_id;
        },
        error: function (jqXHR, textStatus, errorThrown) { 
            console.log(jqXHR); 
        }
    },
    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
        {data: 'necktag', name: 'necktag'},
        {data: 'penjualan_id', name: 'penjualan_id'},
        {data: 'tgl_terjual', name: 'tgl_terjual'},
        {data: 'ket_pembeli', name: 'ket_pembeli'},
        {data: 'pemilik_id', name: 'pemilik_id'},
        {data: 'user_id', name: 'peternak_id'},
        {data: 'ras_id', name: 'ras_id'},
        {data: 'jenis_kelamin', name: 'jenis_kelamin'},
        {data: 'tgl_lahir', name: 'tgl_lahir'},
        {data: 'necktag_ayah', name: 'necktag_ayah'},
        {data: 'necktag_ibu', name: 'necktag_ibu'},
        {data: 'cacat_fisik', name: 'cacat_fisik'},
        {data: 'ciri_lain', name: 'ciri_lain'},
        {data: 'status_ada', name: 'status_ada'},
        {data: 'created_at', name: 'created_at'},
        {data: 'updated_at', name: 'updated_at'},
    ],
});

$('#kawin-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: url_seg+'/laporan/kawin',
        method: 'POST',
        data: function(d){
            d.datefrom = from;
            d.dateto = to;
            d.grup_id = grup_id;
        },
        error: function (jqXHR, textStatus, errorThrown) { 
            console.log(jqXHR); 
        }
    },
    columns: [
        {data: 'id', name: 'id'},
        {data: 'necktag', name: 'necktag'},
        {data: 'necktag_psg', name: 'necktag_psg'},
        {data: 'tgl_kawin', name: 'tgl_kawin'},
        {data: 'created_at', name: 'created_at'},
        {data: 'updated_at', name: 'updated_at'},
    ],
});

$('#sakit-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: url_seg+'/laporan/sakit',
        method: 'POST',
        data: function(d){
            d.datefrom = from;
            d.dateto = to;
            d.grup_id = grup_id;
        },
        error: function (jqXHR, textStatus, errorThrown) { 
            console.log(jqXHR); 
        }
    },
    columns: [
        {data: 'id', name: 'id'},
        {data: 'necktag', name: 'necktag'},
        {data: 'tgl_sakit', name: 'tgl_sakit'},
        {data: 'nama_penyakit', name: 'nama_penyakit'},
        {data: 'obat', name: 'obat'},
        {data: 'lama_sakit', name: 'lama_sakit'},
        {data: 'keterangan', name: 'keterangan'},
        {data: 'created_at', name: 'created_at'},
        {data: 'updated_at', name: 'updated_at'},
    ],
});

$('#perkembangan-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: url_seg+'/laporan/perkembangan',
        method: 'POST',
        data: function(d){
            d.datefrom = from;
            d.dateto = to;
            d.grup_id = grup_id;
        },
        error: function (jqXHR, textStatus, errorThrown) { 
            console.log(jqXHR); 
        }
    },
    columns: [
        {data: 'id', name: 'id'},
        {data: 'necktag', name: 'necktag'},
        {data: 'jenis_kelamin', name: 'jenis_kelamin'},
        {data: 'tgl_perkembangan', name: 'tgl_perkembangan'},
        {data: 'berat_badan', name: 'berat_badan'},
        {data: 'panjang_badan', name: 'panjang_badan'},
        {data: 'lingkar_dada', name: 'lingkar_dada'},
        {data: 'tinggi_pundak', name: 'tinggi_pundak'},
        {data: 'lingkar_skrotum', name: 'lingkar_skrotum'},
        {data: 'keterangan', name: 'keterangan'},
        {data: 'created_at', name: 'created_at'},
        {data: 'updated_at', name: 'updated_at'},
    ],
});

$('#ada-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: url_seg+'/laporan/ada',
        method: 'POST',
        data: function(d){
            d.grup_id = grup_id;
            d.datefrom = from;
            d.dateto = to;
        },
        error: function (jqXHR, textStatus, errorThrown) { 
            console.log(jqXHR); 
        }
    },
    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
        {data: 'necktag', name: 'necktag'},
        {data: 'pemilik_id', name: 'pemilik_id'},
        {data: 'user_id', name: 'peternak_id'},
        {data: 'ras_id', name: 'ras_id'},
        {data: 'kematian_id', name: 'kematian_id'},
        {data: 'penjualan_id', name: 'penjualan_id'},
        {data: 'jenis_kelamin', name: 'jenis_kelamin'},
        {data: 'tgl_lahir', name: 'tgl_lahir'},
        {data: 'bobot_lahir', name: 'bobot_lahir'},
        {data: 'pukul_lahir', name: 'pukul_lahir'},
        {data: 'lama_dikandungan', name: 'lama_dikandungan'},
        {data: 'lama_laktasi', name: 'lama_laktasi'},
        {data: 'tgl_lepas_sapih', name: 'tgl_lepas_sapih'},
        {data: 'necktag_ayah', name: 'necktag_ayah'},
        {data: 'necktag_ibu', name: 'necktag_ibu'},
        {data: 'cacat_fisik', name: 'cacat_fisik'},
        {data: 'ciri_lain', name: 'ciri_lain'},
        {data: 'status_ada', name: 'status_ada'},
        {data: 'created_at', name: 'created_at'},
        {data: 'updated_at', name: 'updated_at'},
    ],
});

$('#dwd-btn').click(function(){
    var param = {
        datefrom: from,
        dateto: to,
        grup_id: grup_id,
    };
    var url = url_seg+"/laporan/export/" + $.param(param);
    window.location = url;
});
