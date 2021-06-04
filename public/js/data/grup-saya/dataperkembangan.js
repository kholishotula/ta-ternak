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
    $('#perkembangan-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: url_seg + "/grup-saya/perkembangan/get",
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'necktag',
                name: 'necktag'
            },
            {
                data: 'tgl_perkembangan',
                name: 'tgl_perkembangan'
            },
            {
                data: 'berat_badan',
                name: 'berat_badan'
            },
            {
                data: 'panjang_badan',
                name: 'panjang_badan'
            },
            {
                data: 'lingkar_dada',
                name: 'lingkar_dada'
            },
            {
                data: 'tinggi_pundak',
                name: 'tinggi_pundak'
            },
            {
                data: 'lingkar_skrotum',
                name: 'lingkar_skrotum'
            },
            {
                data: 'keterangan',
                name: 'keterangan'
            },
            {
                data: 'foto',
                name: 'foto'
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