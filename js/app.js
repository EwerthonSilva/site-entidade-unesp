function initAnimations() {

	setTimeout(function(){
		$('#main-title').removeClass('stop');
	}, 1000);

	setTimeout(function(){
		$('#banner-text-left').removeClass('stop');
		$('#banner-text-right').removeClass('stop');
	}, 1500);

}

$(document).ready(function(){

	$(document).foundation();

	initAnimations();

	$(document).on('click', 'li.prevent-default > a', function(e){
		e.preventDefault();
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

}) //doc.ready
