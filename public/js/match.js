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


$(function () {
    $('.js-select-search').select2({ width: '100%' });
});

$('#match_form').on('submit', function(event){
    event.preventDefault();

    $.ajax({
        url: url_seg+"/match/ternak",
        method: "GET",
        data: $(this).serialize(),
        datatype: "json",
        success: function(data){
            if(data.result == 'gagal'){
                swal({
                    title: "Tidak Boleh!",
                    type: "error",
                    text: data.message,
                });
            }
            else if(data.result == 'berhasil'){
                swal({
                    title: "Boleh!",
                    type: "success",
                    text: "Ternak boleh dikawinkan!",
                });
            }
            else{
                swal({
                    title: "Gagal!",
                    type: "error",
                    text: "Pilih ternak yang ingin dipasangkan!",
                });
            }            
        },
        error: function (jqXHR, textStatus, errorThrown) { 
            console.log(jqXHR); 
        }
    });
});