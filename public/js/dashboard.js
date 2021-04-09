$('#res-refresh').click(function(){
    $('#search_result').hide();
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

$('#search_form').on('submit', function(event){
	event.preventDefault();

    var html = '';
    html = '<tr>';
    html += '<th></th>';
    html += '<th>Necktag</th>'; 
    html += '<th>Jenis Kelamin</th>';
    html += '<th>Ras</th>';
    html += '<th>Tanggal Lahir</th>';
    html += '<th>Blood</th>';
    html += '<th>Peternakan</th>';
    html += '<th>Ayah</th>';
    html += '<th>Ibu</th>';
    html += '</tr>';

	$.ajax({
		url: url_seg+"/search",
		method: "GET",
		data: $(this).serialize(),
		datatype: "json",
		success: function(data){
            $('#search_form')[0].reset();

            if(!data.errors){
                var htmls = '', htmlc = '', htmlgp = '', htmlgc = '';
                var si, sp = [], ss = [], sc = [], sgp = [], sgc = [];
                //0:necktag, 1:jenis_kelamin, 2:ras, 3:tgl_lahir, 4:blood, 5:peternakan, 6:ayah, 7:ibu

                //instance (1)
                $.each(data.result['inst'], function(i, val) {
                    var si1 = data.result['inst'][i].search_inst.split('(');
                    var si2 = si1[1].split(')');
                    si = si2[0].split(','); 
                });
                $('#necktag-r').text(si[0]);
                for(var i = 1; i <= 5; i++){ //instances (hanya butuh index 1 - 5)
                    if(si[i] == ""){
                        si[i] = '-';
                    }
                    $('#inst'+i).text(si[i]);
                }

                //parent (2)
                if(data.result['parent'] != 0){
                    $.each(data.result['parent'], function(i, val) {
                        var sp1 = data.result['parent'][i].search_parent.split('(');
                        var sp2 = sp1[1].split(')');
                        sp[i] = sp2[0].split(','); 

                        for(var j = 1; j <= 8; j++){
                            if(sp[i][j-1] == ""){
                                sp[i][j-1] = '-';
                            } 
                            $('#pr'+i+j).text(sp[i][j-1]);
                        }
                        $('#t-parent').show();
                    });
                    $('#span-parent').empty();
                }
                else{
                    $('#span-parent').html('<p align="center">-</p>');
                    $('#t-parent').hide();
                }

                //sibling
                $('#t-sibling').empty().append(html);
                if(data.result['sibling'] != 0){
                    $.each(data.result['sibling'], function(i, val) {
                        var ss1 = data.result['sibling'][i].search_sibling.split('(');
                        var ss2 = ss1[1].split(')');
                        ss[i] = ss2[0].split(',');

                        htmls = '<tr>'; 
                        htmls += '<td>'+ (i+1) +'</td>';
                        for(var j = 1; j <= 8; j++){
                            if(ss[i][j-1] == ""){
                                ss[i][j-1] = '-';
                            } 
                            htmls += '<td>' + ss[i][j-1] + '</td>';
                        }
                        htmls += '</tr>';
                        $('#t-sibling').append(htmls);
                        $('#t-sibling').show();
                    });    
                    $('#span-sibling').empty();
                }
                else{
                    $('#span-sibling').html('<p align="center">-</p>');
                    $('#t-sibling').hide();
                } 

                //child
                $('#t-child').empty().append(html);
                if(data.result['child'] != 0){
                    $.each(data.result['child'], function(i, val) {
                        var sc1 = data.result['child'][i].search_child.split('(');
                        var sc2 = sc1[1].split(')');
                        sc[i] = sc2[0].split(',');

                        htmlc = '<tr>'; 
                        htmlc += '<td>'+ (i+1) +'</td>';
                        for(var j = 1; j <= 8; j++){
                            if(sc[i][j-1] == ""){
                                sc[i][j-1] = '-';
                            } 
                            htmlc += '<td>' + sc[i][j-1] + '</td>';
                        }
                        htmlc += '</tr>';
                        $('#t-child').append(htmlc);
                        $('#t-child').show();
                    });
                    $('#span-child').empty();
                }
                else{
                    $('#span-child').html('<p align="center">-</p>');
                    $('#t-child').hide();
                }

                //gparent
                $('#t-gp').empty().append(html);
                if(data.result['gparent'] != 0){
                    $.each(data.result['gparent'], function(i, val) {
                        var sgp1 = data.result['gparent'][i].search_gparent.split('(');
                        var sgp2 = sgp1[1].split(')');
                        sgp[i] = sgp2[0].split(',');

                        htmlgp = '<tr>'; 
                        htmlgp += '<td>'+ (i+1) +'</td>';
                        for(var j = 1; j <= 8; j++){
                            if(sgp[i][j-1] == ""){
                                sgp[i][j-1] = '-';
                            } 
                            htmlgp += '<td>' + sgp[i][j-1] + '</td>';
                        }
                        htmlgp += '</tr>';
                        $('#t-gp').append(htmlgp);
                        $('#t-gp').show();   
                    });
                    $('#span-gp').empty();                    
                }
                else{
                    $('#span-gp').html('<p align="center">-</p>');
                    $('#t-gp').hide();
                }

                //gchild
                $('#t-gc').empty().append(html);
                if(data.result['gchild'] != 0){
                    $.each(data.result['gchild'], function(i, val) {
                        var sgc1 = data.result['gchild'][i].search_gchild.split('(');
                        var sgc2 = sgc1[1].split(')');
                        sgc[i] = sgc2[0].split(',');

                        htmlgc = '<tr>'; 
                        htmlgc += '<td>'+ (i+1) +'</td>';
                        for(var j = 1; j <= 8; j++){
                            if(sgc[i][j-1] == ""){
                                sgc[i][j-1] = '-';
                            } 
                            htmlgc += '<td>' + sgc[i][j-1] + '</td>';
                        }
                        htmlgc += '</tr>';
                        $('#t-gc').append(htmlgc);
                        $('#t-gc').show();
                    });
                    $('#span-gc').empty();
                }
                else{
                    $('#span-gc').html('<p align="center">-</p>');
                    $('#t-gc').hide();
                }
                

                //show body
                $('#exist').show();
                $('#not-exist').hide();
            }
            else{ //jika data error
                $('#necktag-r').text(data.errors.necktag);
                $('#not-exist').text(data.errors.result);
                $('#not-exist').show();
                $('#exist').hide();
            }

			$('#search_result').show();
		},
        error: function (jqXHR, textStatus, errorThrown) { 
            console.log(jqXHR); 
        }
	});
});