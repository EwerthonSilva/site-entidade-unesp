/* handler for the message setTimeout */
var message_timer;

function showDboMessage() {
	showPeixeMessage();
}

function setMessage(message) {
	$('div.wrapper-message').html(message);
}

function activeMainNav(menu) {
	$('#menu-'+menu).addClass('active');
}

function openDboModal(url, tamanho, callback) {
	if(typeof tamanho == 'undefined'){
		tamanho = 'medium';
	}
	$('#modal-dbo-'+tamanho).foundation('reveal', 'open', {
		url: url,
		success: function(){
			setTimeout(function(){
				if(typeof callback == 'function'){
					callback(result);
				}
				else if(typeof window[callback] == 'function'){
					window[callback]();
				}
				else if(typeof callback == 'string'){
					eval(callback);
				}
			}, 200);
		}
	})
}

$(document).ready(function(){
	//fade nas mensagens

	showDboMessage();

	$(document).on('click', '[rel="redirect"]', function(e){
		e.preventDefault();
		e.stopPropagation();
		clicado = $(this);
		document.location = clicado.data('url');
	});

	$(document).on('click', '[rel^="lightbox"]', function(e){
		e.preventDefault();
		clicado = $(this);
		$.colorbox({
			href:clicado.attr('href'),
			fixed: true,
			maxWidth: '95%',
			maxHeight: '95%',
		});
	});

	//change password
	$(document).on('click', '.trigger-change-password', function(e){
		e.preventDefault();
		$('#modal-change-password').foundation('reveal', 'open', {
			url: 'dbo-modal-change-password.php'
		});
	});

	//abrindo modais com trigger
	$(document).on('click', '.trigger-dbo-modal', function(e){
		e.preventDefault();
		clicado = $(this);
		openDboModal(clicado.data('url'), clicado.data('tamanho'), clicado.data('callback'));
	});

	$(document).on('click', '.trigger-colorbox-modal', function(e){
		e.preventDefault();
		clicado = $(this);
		openColorBoxModal(clicado.data('url'), clicado.data('width'), clicado.data('height'), clicado.data());
	});

	$(document).on('click', '.trigger-tablesorter-filters', function(e){
		e.stopPropagation();
		clicado = $(this);
		if($(this).data('tabela')){
			filter_row = $($(this).data('tabela')).find('.tablesorter-filter-row');
		}
		else {
			filter_row = $(this).closest('table').find('.tablesorter-filter-row');
		}
		if(filter_row.is(':hidden')){
			filter_row.fadeIn('fast');			
		}
		else {
			filter_row.hide();
		}
	});

	// DBOTAG-F6RM - Accordions
	$(document).on('click', 'ul.accordion[data-accordion] .accordion-navigation > a', function(e){
		e.preventDefault();
		clicado = $(this);
		if(!clicado.next('.content').hasClass('active')){
			open = clicado.closest('ul[data-accordion]').find('.accordion-navigation > .content.active');
			if(open.length){
				open.slideUp('fast', function(){
					$(this).removeClass('active');
					clicado.next('.content').slideDown('fast', function(){
						$(this).addClass('active');
					});
				});
			}
			else {
				clicado.next('.content').slideDown('fast', function(){
					$(this).addClass('active');
				});
			}
		}
	});

	//Settings boxes
	$(document).on('click', '.toggle-settings-box', function(){
		c = $(this);
		t = $('#'+c.data('settings-box'));
		if(t.is(':visible')){
			t.slideUp('fast', 'easeInCubic');
		}
		else {
			t.slideDown('fast', 'easeOutCubic');
		}
	});

	//Preferences
	$(document).on('click', '[data-dbo-set-pref]', function(e){
		var c = this;
		mk = (typeof c.dataset.meta_key !== 'undefined' ? c.dataset.meta_key : null);
		jk = c.dataset.pref_key;
		jv = c.dataset.pref_value;
		peixeJSONSilent(DBO_URL+'/core/dbo-meta-ajax.php?action=set-pref', {
			meta_key: mk,
			json_key: jk,
			json_value: jv
		}, null, true);
		if(typeof c.dataset.toggle !== 'undefined'){
			c.dataset.pref_value = jv == 'true' ? 'false' : 'true';
		}
	});

	$(document).on('click', '.input-content-tools', function(){
		target = $('.ct-ignition__button--edit:visible');
		if(target.length){
			target.trigger('click');
		}
	});

});
