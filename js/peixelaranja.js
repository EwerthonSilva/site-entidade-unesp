//função para mostrar e esconder as mensagens de erro/etc.
function showPeixeMessage() {
	if($('div.wrapper-message').text() != ''){
		message_timer = setTimeout(function(){
			$('div.wrapper-message').animate({
				height: '60px',
				opacity: 1
			}, 500, function(){
				message_timer = setTimeout(function(){
					$('div.wrapper-message').animate({
						height: '0',
						opacity: 0
					}, 500, function(){
						$('div.wrapper-message').html('');
					});
				}, 3000);
			});
		}, 300);
	}
}

//funcão para setar a mensagem
function setPeixeMessage(message) {
	$('div.wrapper-message').html(message);
}

function showPeixeLoader() {
	$('.peixe-ajax-loader').fadeIn('fast');
	$('.peixe-screen-freezer').show();
}

//function que funciona como o .html() do jQuery, mas com um efeito de fade
(function($) {
	$.fn.fadeHtml = function(content, callback) {
		return this.each(function() {
			$(this).fadeTo('fast', 0, function(){
				$(this).html(content);
				$(this).fadeTo('fast', 1, callback);
			});
		});
	}
})(jQuery);	

//mostra o loader de AJAX
function hidePeixeLoader() {
	$('.peixe-ajax-loader').delay(200).fadeOut('fast');
	$('.peixe-screen-freezer').hide();
}

//funciona igual ao .post() de jQuery, mas com loader
function peixePost(url, args, callback) {
	showPeixeLoader();
	$.post(url,args,callback).complete(function(){ hidePeixeLoader() });
}

//funciona igual ao .get() de jQuery, mas com loader
function peixeGet(url, args, callback) {
	showPeixeLoader();
	$.get(url,args,callback).complete(function(){ hidePeixeLoader() });
}

$(document).ready(function(){

	//mostra mensagens
	showPeixeMessage();

	//botao de submit que só funciona com javascript, e impede dupla submissão
	$(document).on('click', 'form .submitter', function(){
		$(this).attr('disabled', 'disabled');
		$(this).closest('form').submit();
	})

	//some com a mensagem se clicada
	$(document).on('click', 'div.wrapper-message', function(){
		clearTimeout(message_timer);
		$(this).css('height', '0px');
		$(this).css('opacity', '0');
	})

	//tira o destaque de erro dos inputs, quando mudados
	$(document).on('focus', 'form .error', function(){
		$(this).removeClass('error');
	})

	//colocando * nos requireds
	$('form .required').closest('.item').find('label').append(" <span style='color: red'>*</span>");

	//colcando ajax loader e screen freezer
	$('body:not(:has(.peixe-ajax-loader))').prepend('<div class="peixe-ajax-loader">Carregando...</div>');
	$('body:not(:has(.peixe-screen-freezer))').prepend('<div class="peixe-screen-freezer"></div>');
	$('body:not(:has(.wrapper-message))').prepend('<div class="wrapper-message"></div>');

}) //doc.ready