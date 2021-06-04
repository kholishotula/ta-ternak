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
    $('#ternak-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: url_seg + "/grup-saya/ternak/get",
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'necktag',
                name: 'necktag'
            },
            {
                data: 'ras_id',
                name: 'ras_id'
            },
            {
                data: 'user_id',
                name: 'user_id'
            },
            {
                data: 'pemilik_id',
                name: 'pemilik_id'
            },
            {
                data: 'kematian_id',
                name: 'kematian_id'
            },
            {
                data: 'penjualan_id',
                name: 'penjualan_id'
            },
            {
                data: 'jenis_kelamin',
                name: 'jenis_kelamin'
            },
            {
                data: 'tgl_lahir',
                name: 'tgl_lahir'
            },
            {
                data: 'bobot_lahir',
                name: 'bobot_lahir'
            },
            {
                data: 'pukul_lahir',
                name: 'pukul_lahir'
            },
            {
                data: 'lama_dikandungan',
                name: 'lama_dikandungan'
            },
            {
                data: 'lama_laktasi',
                name: 'lama_laktasi'
            },
            {
                data: 'tgl_lepas_sapih',
                name: 'tgl_lepas_sapih'
            },
            {
                data: 'necktag_ayah',
                name: 'necktag_ayah'
            },
            {
                data: 'necktag_ibu',
                name: 'necktag_ibu'
            },
            {
                data: 'cacat_fisik',
                name: 'cacat_fisik'
            },
            {
                data: 'ciri_lain',
                name: 'ciri_lain'
            },
            {
                data: 'status_ada',
                name: 'status_ada'
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