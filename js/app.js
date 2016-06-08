$(document).foundation();

$(document).ready(function(){
	$('.cpf').inputmask('999.999.999-99');
});
$(document).on('change', 'select[name=formacao]', function(){
	c = $(this);
	if(c.val() == 'Graduação'){
		$('#input-graduacao').fadeIn('fast', function(){
		});
	}
	else {
		$('#input-graduacao').fadeOut('fast');
	}

});

$(document).on('change', 'select[name=faculdade]', function(){
	c = $(this);
	if(c.val() == 'Outras Instituições'){
		$('#outra-instituicao').fadeIn('fast', function(){
		});
	}
	else {
		$('#outra-instituicao').fadeOut('fast');
	}

});
