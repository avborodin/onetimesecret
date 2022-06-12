$(document).ready(function(){
	$('#pwd1, #pwd2').on('keyup', function () {
		if($('#pwd').is(":visible")){
			if ($('#pwd1').val() != '' && $('#pwd2').val() != '' && $('#pwd1').val() == $('#pwd2').val()) {
				$("#btn_create").attr("disabled", false);
				$('#pwd_invalid').hide();
				$('.pwd').removeClass('is-invalid')
			} else {
				$("#btn_create").attr("disabled", true);
				$('#txt_invalid').hide();
				$('#pwd_invalid').show();
				$('#pwd_invalid').html('Not Matching').css('color', 'red');
				$('.pwd').addClass('is-invalid');
			}
		}
	});
	
	let form = document.getElementById('form_contents');
	form.addEventListener('submit', function(event) {
		if (form.checkValidity() === false) {
			event.preventDefault();
			event.stopPropagation();
		}
		form.classList.add('was-validated');
	}, false);
	form.querySelectorAll('.form-control').forEach(input => {
		input.addEventListener(('input'), () => {
			if (input.checkValidity()) {
				input.classList.remove('is-invalid')
				input.classList.add('is-valid');
			} else {
				input.classList.remove('is-valid')
				input.classList.add('is-invalid');
			}
			var is_valid = $('.form-control.required').length === $('.form-control.is-valid').length;
			$("#btn_create").attr("disabled", !is_valid);
		});
	});

	$('#pwd').hide();
	$( "#btn_options" ).click(function() {
		if($('#pwd').is(":visible")){
			$('#pwd').hide();
			$('#pwd1,#pwd2').removeClass('required');
			$('#pwd1,#pwd2').removeClass('is-valid');
			$('#pwd1,#pwd2').removeClass('is-invalid');
			$('#pwd1,#pwd2').val('');
			$('#pwd1,#pwd2').prop("required", false);
			$(this).html('Show options');
			if($('#contents').val() !=''){
				$("#btn_create").attr("disabled", false);
			}
		}else{
			$('#pwd').show();
			$('#pwd1,#pwd2').addClass('required');
			$('#pwd1,#pwd2').prop("required", true);
			$("#btn_create").attr("disabled", true);
			$(this).html('Disable options');
		}
	});
});

$('#btn_create').click(function(){
	var contents = $('#contents').val();
	var pwd1 = $('#pwd1').val();

	$.ajax({
		url: 'index.php',
		method: 'post',
		dataType: 'html',
		data: {'contents':contents, 'pwd1':pwd1},
		success: function(data){
			$('.contents').html(data);
		}
	});
});

function copyLink()
{
	var copyLink = document.getElementById("link");
	copyLink.select();
	copyLink.setSelectionRange(0, 99999); 
	navigator.clipboard.writeText(copyLink.value);
}