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


$('#edit_profil').click(function(){	
	$('#form_result').html('');

	$.ajax({
		url: url_seg+"/profile/edit",
		datatype: "json",
		success: function(data){
			$('#name').val(data.result.name);
			$('#username').val(data.result.username);
			$('#email').val(data.result.email);
			$('#hidden_id').val(data.result.id);
	    	$('#ubahProfilModal').modal('show');
		}
	});
});

$('#ubah_data_form').on('submit', function(event){
	event.preventDefault();
	var updateId = $('#hidden_id').val();

	$.ajax({
		url: url_seg+"/profile/edit",
		method: "PUT",
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
				location.reload();
			}
			$('#form_result').html(html);
		}
	});
});

$('#ubah_pass_form').on('submit', function(event){
	event.preventDefault();
	var updateId = $('#hidden_id').val();

	$.ajax({
		url: url_seg+"/password/change",
		method: "POST",
		data: $(this).serialize(),
		datatype: "json",
		success: function(data){
			var html = '';
			if (data.errors) {
				html = '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
				for (var count = 0; count < data.errors.length; count++) {
					html += '<p>' + data.errors[count] + '</p>';
				}
				html += '</div>';
			}
			if (data.error) {
				html = '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' + data.error + '</div>';
			}
			if (data.success) {
				html = '<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' + data.success + '</div>';
				location.reload();
			}
			$('#form_result_pass').html(html);
		}
	});
});

$("#password-field-1").click(function() {
	$(this).html('<i class="material-icons">visibility_off</i>');

  	var input = $("#current_password");
  	if (input.attr("type") === "password") {
    	input.attr("type", "text");
	}else {
    	input.attr("type", "password");
    	$(this).html('<i class="material-icons">visibility</i>');
  	}
});

$("#password-field-2").click(function() {
	$(this).html('<i class="material-icons">visibility_off</i>');

  	var input = $("#password");
  	if (input.attr("type") === "password") {
    	input.attr("type", "text");
	}else {
    	input.attr("type", "password");
    	$(this).html('<i class="material-icons">visibility</i>');
  	}
});

$("#password-field-3").click(function() {
	$(this).html('<i class="material-icons">visibility_off</i>');

  	var input = $("#password-confirm");
  	if (input.attr("type") === "password") {
    	input.attr("type", "text");
	}else {
    	input.attr("type", "password");
    	$(this).html('<i class="material-icons">visibility</i>');
  	}
});