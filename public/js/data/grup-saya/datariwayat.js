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

$(function() {
    $('#riwayat-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: url_seg + "/grup-saya/riwayat/get",
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'necktag',
                name: 'necktag'
            },
            {
                data: 'nama_penyakit',
                name: 'nama_penyakit'
            },
            {
                data: 'tgl_sakit',
                name: 'tgl_sakit'
            },
            {
                data: 'lama_sakit',
                name: 'lama_sakit'
            },
            {
                data: 'obat',
                name: 'obat'
            },
            {
                data: 'keterangan',
                name: 'keterangan'
            },
            {
                data: 'created_at',
                name: 'created_at'
            },
            {
                data: 'updated_at',
                name: 'updated_at'
            },
        ]
    });
});