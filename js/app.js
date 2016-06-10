$(document).foundation();

$(document).ready(function(){
	$('.cpf').inputmask('999.999.999-99');
});
$(document).on('change', 'select[name=formacao]', function(){
	c = $(this);
	if(c.val() == 'Graduação'){
		$('#input-graduacao').fadeIn('fast', function(){
		}).find('input').prop('required', true);
	}
	else {
		$('#input-graduacao').fadeOut('fast').find('input').prop('required', false);
	}

});

$("input[type='checkbox']").on('change', function(){
	if($('input.atividade-paga:checked').length){
		$('#forma-pagamento-input').fadeIn();
	}else{
		$('#forma-pagamento-input').fadeOut();
	}
});

$(document).on('change', 'select[name=faculdade]', function(){
	c = $(this);
	if(c.val() == 'Outras Instituições'){
		$('#outra-instituicao').fadeIn('fast', function(){

		}).find('input').prop('required', true);
	}
	else {
		$('#outra-instituicao').fadeOut('fast').find('input').prop('required', false);
	}

});
