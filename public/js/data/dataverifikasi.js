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
    $('#verif-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: url_seg + "/verifikasi/users",
        columns: [{
                data: 'id',
                name: 'id'
            },
            {
                data: 'grup_id',
                name: 'grup_id'
            },
            {
                data: 'ktp_user',
                name: 'ktp_user'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'username',
                name: 'username'
            },
            {
                data: 'email',
                name: 'email'
            },
            {
                data: 'created_at',
                name: 'created_at'
            },
            {
                data: 'verified_at',
                name: 'verified_at'
            },
            {
                data: 'action',
                name: 'action'
            },
        ]
    });
});