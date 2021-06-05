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

$('#exist').hide();
$('#not-exist').hide();

$('#search_form').on('submit', function(event){
	event.preventDefault();
    $('#exist').hide();
    
    var family = [];

	$.ajax({
		url: url_seg+"/search",
		method: "GET",
		data: $(this).serialize(),
		datatype: "json",
		success: function(data){
            $('#search_form')[0].reset();

            if(!data.errors){
                $('#title').text('Detail Kambing - ' + data.inst.necktag);
                $('#jk').text(data.inst.jenis_kelamin);
                $('#ras').text(data.inst.jenis_ras);
                $('#tgl_lahir').text(data.inst.tgl_lahir);
                $('#pemilik').text(data.inst.pemilik);
                $('#peternak').text(data.inst.peternak);
        
                // jadikan seluruh data keluarga menjadi kesatuan array family
                if(data.gparents != null){
                    for(i = 0; i<data.gparents.length; i++){
                        family.push(data.gparents[i])
                    }
                }
                if(data.parents != null){
                    for(i = 0; i<data.parents.length; i++){
                        family.push(data.parents[i])
                    }
                }
                if(data.spouse[0] != null){
                    family.push(data.spouse[0]);
                }
                family.push(data.inst);
                if(data.siblings != null){
                    for(i = 0; i<data.siblings.length; i++){
                        family.push(data.siblings[i])
                    }
                }
                if(data.spouse != null){
                    if(data.children != null){
                        for(i = 0; i<data.children.length; i++){
                            family.push(data.children[i])
                        }
                    }
                    if(data.gchildren != null){
                        for(i = 0; i<data.gchildren.length; i++){
                            family.push(data.gchildren[i])
                        }
                    }
                }

                $('#exist').show();
                buildData(family);
                $('#not-exist').hide();
            }
            else{ //jika data error
                $('#exist').hide();
                $('#not-exist').show();
                console.log(data.errors.result);
                $('#not-exist').text(data.errors.result);
            }
		},
        error: function (jqXHR, textStatus, errorThrown) { 
            console.log(jqXHR); 
        }
	});
});

function buildData(data){
    var family = [];
    var i, color, parent;

    for(i=0;i<data.length;i++){
        parent = '';
        if(data[i].jenis_kelamin == 'Jantan'){
            color = 'blue';
        }
        else{
            color = 'red';
        }
        if(data[i].ayah != null){
            if(checkParentInstance(family, data[i].ayah)){
                parent += data[i].ayah;
            }
        }
        if(data[i].ibu != null){
            if(checkParentInstance(family, data[i].ibu)){
                if(parent != ''){
                    parent += ',';
                }
                parent += data[i].ibu;
            }
        }
        if(parent != ''){
            family.push({
                name: data[i].necktag,
                id: data[i].necktag,
                color: color,
                parent: parent,
                attributes: {
                    'pemilik': data[i].pemilik,
                    'peternak': data[i].peternak,
                    'ras': data[i].jenis_ras,
                    'lahir': data[i].tgl_lahir,
                }
            });
        }
        else{
            family.push({
                name: data[i].necktag,
                id: data[i].necktag,
                color: color,
                attributes: {
                    'pemilik': data[i].pemilik,
                    'peternak': data[i].peternak,
                    'ras': data[i].jenis_ras,
                    'lahir': data[i].tgl_lahir,
                }
            });
        }
    }
    createChart(family);
    // console.log(family);
}

function checkParentInstance(family, key){
    var found = false;
    for(var i = 0; i < family.length; i++) {
        if (family[i].name == key) {
            found = true;
            break;
        }
    }
    return found;
}

function createChart(data){
    var chart = JSC.chart('chartDiv', { 
        debug: true, 
        type: 'organization down', 
        legend_visible: true, 
        series: [ 
            { 
            line_color: '#747c72', 
            defaultPoint: { 
                label: { 
                    text: '<b>%name</b>', 
                    autoWrap: false
                }, 
                annotation: { 
                    padding: 9, 
                    corners: [ 
                        'cut', 
                        'square',   
                        'cut', 
                        'square'
                    ], 
                    margin: [15, 5, 10, 0] 
                }, 
                color: '#dcead7', 
                tooltip: 
                'necktag: <b>%name</b><br/>pemilik: %pemilik<br/>peternak: %peternak<br/>ras: %ras<br/>tgl lahir: %lahir'
            }, 
            points: data
            } 
        ], 
    }); 
}