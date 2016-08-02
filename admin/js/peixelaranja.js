
//modificando o prototype de algumas classes
Date.prototype.dateTime = function() {
	var y = this.getFullYear().toString();
	var m = (this.getMonth()+1).toString(); // getMonth() is zero-based
	var d = this.getDate().toString();
	var h = this.getHours().toString();
	var i = this.getMinutes().toString();
	return y + '-' + (m[1] ? m : '0'+m) + '-' + (d[1] ? d : '0'+d) + ' ' + (h[1] ? h : '0'+h) + ':' + (i[1] ? i : '0'+i);
};

//exemplo: string.split('-').list('key', 'value'); <--- variaveis são passadas como string para o list.
Array.prototype.list = function()
{
	var 
		limit = this.length,
		orphans = arguments.length - limit,
		scope = orphans > 0  && typeof(arguments[arguments.length-1]) != "string" ? arguments[arguments.length-1] : window 
	;

	while(limit--) scope[arguments[limit]] = this[limit];

	if(scope != window) orphans--;

	if(orphans > 0)
	{
		orphans += this.length;
		while(orphans-- > this.length) scope[arguments[orphans]] = null;  
	}  
}

var peixe_message_timer;
var wrapper_peixe_message;
var peixe_fade_html_timer = 200;
var peixe_current_url = document.URL;

function showPeixeMessage() {
	clearTimeout(peixe_message_timer);
	wrapper_peixe_message = $('div.wrapper-message');
	if(wrapper_peixe_message.text() != '') {
		peixe_message_timer = setTimeout(function(){
			slidePeixeMessageDown();
			peixe_message_timer = setTimeout(function(){
				slidePeixeMessageUp();
			}, 4500);
		}, 300);
	}
}

function slidePeixeMessageDown() {
	clearTimeout(peixe_message_timer);
	wrapper_peixe_message.removeClass('closed');
}

function slidePeixeMessageUp() {
	clearTimeout(peixe_message_timer);
	wrapper_peixe_message.addClass('closed');
	peixe_message_timer = setTimeout(function(){
		removePeixeMessage();
	}, 1500);
}

function peixeQueryString(obj){
	ret = {};
	if(obj){
		parts = obj.split('&');
		parts.forEach(function(foo) {
			foo.split(/=(.*)/).list('key', 'value');
			ret[key] = value;
		});
	}
	return ret;
}

function isJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

function keepUrl(foo, url) {
	peixe_current_url.split('?').list('host', 'query_string');
	query_string = peixeQueryString(query_string);
	if(typeof foo != 'undefined' && foo.length){
		vars = foo.split('&');
		vars.forEach(function(foo2) {
			if(foo2[0] == '!'){
				delete query_string[foo2.replace('!', '')];
			}
			else {
				foo2.split('=').list('k', 'v');
				query_string[k] = v;
			}
		});
	}
	ar = [];
	for(var key in query_string)
	{
		ar.push(key+'='+query_string[key]);
	}
	return host+(ar.length ? '?' : '')+ar.join('&');
}

function peixeJSONSilent(action, args, callback, log, method, error_message, mode) {
	return peixeJSON(action, args, callback, log, method, error_message, 'silent');
}

function peixeJSON(action, args, callback, log, method, error_message, mode) {
	if(typeof method == 'undefined' || method == 'post'){
		function_name = 'peixePost';
	}
	else {
		function_name = 'peixeGet';
	}
	window[function_name](
		action,
		args,
		function(data) {
			//mostrando log
			if(log == true){
				console.log(data);
			}
			try{
				var result = $.parseJSON(data);
				if(result.message){
					setPeixeMessage(result.message);
					showPeixeMessage();
				}
				if(result.reload){
					var html = '';
					url = typeof result.reload_url == 'undefined' ? peixe_current_url : result.reload_url;
					peixeGet(url, function(d) {
						html = $.parseHTML(d);
						result.reload.forEach(function(value) {
							peixeReload(value, html);
						});
						(result.reload_eval ? eval(result.reload_eval) : null);
					}, null, mode)
				}
				if(result.html){
					for(var key in result.html)
					{
						$(key).fadeHtml(result.html[key]);
					}
				}
				if(result.callback){
					result.callback.forEach(function(value) {
						if (typeof window[value] == 'function') {
							window[value]();
						}
					});
				}
				if(result.eval){
					eval(result.eval)
				}
				//tratando o callback customizado
				if(typeof callback == 'function'){
					callback(result);
				}
				else if(typeof window[callback] == 'function'){
					window[callback](result);
				}
				else if(typeof callback == 'string'){
					eval(callback);
				}
				if(result.redirect){
					window.location = result.redirect;
				}
			}catch(e){
				if(error_message){
					alert(error_message);
				} else {
					alert(e); //error in the above string(in this case,yes)!
				}
			}
		},
		mode
	)
	return false;
}

function removePeixeMessage() {
	clearTimeout(peixe_message_timer);
	wrapper_peixe_message.addClass('no-transition').addClass('closed').html('').removeClass('no-transition');
}

//funcão para setar a mensagem
function setPeixeMessage(message) {
	$('div.wrapper-message').html(message);
}

function peixeReload(item, html, callback){
	content = $(html).find(item).html();
	if(typeof content === 'undefined'){
		$(item).fadeOut(function(){
			$(this).remove();
		});
	}
	else {
		if($(item).is(':visible')){
			$(item).fadeHtml(content, callback);
		}
		else {
			$(item).html(content, callback);
		}
	}
}

function showPeixeLoader() {
	$('.peixe-ajax-loader').fadeIn('fast');
	$('.peixe-screen-freezer').show();
}

(function($) {

	//function que funciona como o .html() do jQuery, mas com um efeito de fade
	$.fn.fadeHtml = function(content, callback) {
		return this.each(function() {
			$(this).fadeTo(peixe_fade_html_timer, 0, function(){
				$(this).html(content);
				$(this).fadeTo(peixe_fade_html_timer, 1, callback);
			});
		});
	}

	//função para remover determinados inputs do Foundation Abide
	$.fn.peixeUnrequire = function(callback) {
		var size = this.length-1;
		return this.each(function(i) {
			if(this.nodeName != 'INPUT' && this.nodeName != 'SELECT' && this.nodeName != 'TEXTAREA'){
				$(this).find('[required]').removeAttr('required').removeClass('required').attr('maybe-required', '');
			}
			else {
				$(this).removeAttr('required').removeClass('required').attr('maybe-required', '');
			}
			if(size == i){
				if(typeof callback == 'function'){
					callback.call(this);
				}
			}
		});
	}

	//função para adicionar determinados inputs no Foundation Abide
	$.fn.peixeRequire = function(callback) {
		var size = this.length-1;
		return this.each(function(i) {
			if(this.nodeName != 'INPUT' && this.nodeName != 'SELECT' && this.nodeName != 'TEXTAREA'){
				$(this).find('[maybe-required]').removeAttr('maybe-required').addClass('required').attr('required', '');
			}
			else {
				$(this).removeAttr('maybe-required').addClass('required').attr('required', '');
			}
			if(size == i){
				if(typeof callback == 'function'){
					callback.call(this);
				}
			}
		});
	}

	//funcao para equalizar a altura dos elementos
	$.fn.peixeEqualizeHeights = function(callback){

		var size = this.length-1;
		var height = 0, reset = "auto";
  
		return this
			.css("height", reset)
			.each(function() {
				height = Math.max(height, this.offsetHeight);
			})
			.css("height", height)
			.each(function(i) {
				var h = this.offsetHeight;
				if (h > height) {
					$(this).css("height", height - (h - height));
				};
				if(size == i){
					if(typeof callback == 'function'){
						callback.call(this);
					}
				}
			});
	}

})(jQuery);	

//mostra o loader de AJAX
function hidePeixeLoader() {
	$('.peixe-ajax-loader').delay(200).fadeOut('fast');
	$('.peixe-screen-freezer').hide();
}

//funciona igual ao .post() de jQuery, mas com loader
function peixePost(url, args, callback, mode, done) {
	if(mode != 'silent') showPeixeLoader(); 
	$.post(url,args,callback).done(function(){ hidePeixeLoader() });
}

//funciona igual ao .get() de jQuery, mas com loader
function peixeGet(url, args, callback, mode, d) {
	if(mode != 'silent') showPeixeLoader(); 
	$.get(url,args,callback).done(function(){ hidePeixeLoader(); if(typeof d == 'function') d() });
}

function peixeAddRequiredBullets() {
	//colocando * nos requireds
	$('form .required, form [required]').closest('.item').find('label:not(:has(.bullet-required)):first').append(' <span class="bullet-required">*</span>');
}

//funcoes para tratamento de upload de arquivos com ajax. O markup está setado para foundation

// Function that will allow us to know if Ajax uploads are supported
function supportAjaxUploadWithProgress() {
	return supportFileAPI() && supportAjaxUploadProgressEvents() && supportFormData();

	// Is the File API supported?
	function supportFileAPI() {
		var fi = document.createElement('INPUT');
		fi.type = 'file';
		return 'files' in fi;
	};

	// Are progress events supported?
	function supportAjaxUploadProgressEvents() {
		var xhr = new XMLHttpRequest();
		return !! (xhr && ('upload' in xhr) && ('onprogress' in xhr.upload));
	};

	// Is FormData supported?
	function supportFormData() {
		return !! window.FormData;
	}
}

function peixeAjaxFileUploadSingleFile(input_id, action, data) {

	//console.log(action);
	input = $('#'+input_id);
	var formData = new FormData();

	// FormData only has the file
	formData.append('peixe_ajax_file_upload_file', input[0].files[0]);

	for(var prop in data) { 
		if (data.hasOwnProperty(prop)) {
			formData.append(prop, data[prop]);
		}
	}
	// Code common to both variants
	peixeAjaxFileUploadSendXHRequest(formData, action, input_id);
}

// Once the FormData instance is ready and we know
// where to send the data, the code is the same
// for both variants of this technique
function peixeAjaxFileUploadSendXHRequest(formData, uri, input_id) {
	// Get an XMLHttpRequest instance
	var xhr = new XMLHttpRequest();

	// Set up events
	xhr.upload.addEventListener('loadstart', peixeAjaxFileUploadonloadstartHandler, false);
	xhr.upload.addEventListener('progress', peixeAjaxFileUploadonprogressHandler, false);
	xhr.upload.addEventListener('load', peixeAjaxFileUploadonloadHandler, false);
	xhr.upload.input_id = input_id;
	xhr.addEventListener('readystatechange', peixeAjaxFileUploadonreadystatechangeHandler, false);
	xhr.input_id = input_id;

	// Set up request
	xhr.open('POST', uri, true);

	// Fire!
	xhr.send(formData);
}

// Handle the start of the transmission
function peixeAjaxFileUploadonloadstartHandler(evt) {
	//var container = $("#"+evt.target.input_id).nextAll('.peixe-ajax-upload-status').first();
	var container = $("#"+evt.target.input_id).next('label');
	//upload started
	container.prev('input[type="file"]').hide();
	container.find('i').removeClass().addClass('fa fa-fw fa-spinner fa-spin');
}

// Handle the end of the transmission
function peixeAjaxFileUploadonloadHandler(evt) {
	//var container = $("#"+evt.target.input_id).nextAll('.peixe-ajax-upload-status').first();
	//upload ended
}

// Handle the progress
function peixeAjaxFileUploadonprogressHandler(evt) {
	/*var container = $("#"+evt.target.input_id).nextAll('.peixe-ajax-upload-status').first();
	var progress_bar = container.find('.progress .meter');
	var percent = evt.loaded/evt.total*100;
	progress_bar.css('width', percent+'%');*/
}

// Handle the response from the server
function peixeAjaxFileUploadonreadystatechangeHandler(evt) {
	var status, text, readyState;
	var input = $("#"+evt.target.input_id);
	var container = input.nextAll('.peixe-ajax-upload-status').first();
	var label = input.next('label');

	input.trigger('uploadStart', {});

	try {
		readyState = evt.target.readyState;
		text = evt.target.responseText;
		status = evt.target.status;
	}
	catch(e) {
		return;
	}

	if (readyState == 4 && status == '200' && evt.target.responseText) {
		console.log(evt.target.responseText);
		var data = $.parseJSON(evt.target.responseText);
		if(data.error){
			//erro de qualquer natureza na hora do upload
			label.removeClass('secondary').addClass('alert').find('i').removeClass().addClass('fa fa-fw fa-times');
			label.find('.text').html('Erro: '+data.error);
			/*container.find('.upload-sending').fadeOut('fast', function(){
				container.find('.upload-error').fadeIn('fast').find('span').text();
			});*/
			input.trigger('uploadCanceled', {});
		}
		else {
			//tudo ok, disparar evento
			//var event = new CustomEvent('upload-done', { 'detail': { 'old_file_name': data.old_name, 'new_file_name': data.name } });
			//input[0].dispatchEvent(event);
			input.val('').trigger('uploadDone', { 'old_file_name': data.old_name, 'new_file_name': data.name, data: data });
			if(input.prop('required')){
				input.attr('data-ex-required', true);
				input.prop('required', false);
			}

			container.find('input[type="text"]').val(data.name);
			label.removeClass('alert secondary').find('.text').text(data.old_name);
			label.removeClass('alert secondary').find('i').removeClass().addClass('fa fa-fw fa-check');
			label.attr('title', data.old_name+' - Pressione CTRL+CLICK para remover o arquivo.');
			/*container.find('.upload-sending').hide();
			container.find('.upload-success').show();
			container.find('.upload-success').fadeOut('fast', function(){
				container.find('.upload-progress').fadeOut('fast', function(){
					container.find('.upload-file-placeholder').fadeIn('fast').find('.uploaded-file').text(data.old_name);
				})
			})*/
		}
	}
}

//reset the ajax file upload
function peixeAjaxFileUploadRetry(input_id) {
	console.log('retry upload');
	var input = $('#'+input_id);
	var container = input.nextAll('.peixe-ajax-upload-status').first();
	var label = input.next('label');

	//reseta todo o campo de upload
	//container.find('.upload-progress:visible').hide();
	//container.find('.upload-sending:visible').hide();
	//container.find('.upload-success:visible').hide();
	//container.find('.upload-error:visible').hide();
	//container.find('.upload-file-placeholder:visible').hide();
	container.find('input[type="text"]').val('');
	input.val('').fadeIn('fast');
	label.addClass('secondary').find('i').removeClass().addClass(label.data('icon'));
	label.find('.text').text(label.data('text'));
	label.attr('title', '');
	if(input.data('ex-required') == true){
		console.log('ex_required');
		input.prop('required', true);
		input.attr('data-ex-required', false);
	}
}

function peixeAjaxFileUploadInit() {
	$('input[type="file"][peixe-ajax-file-upload]').each(function(){
		var foo = $(this);
		//verifica se a função já não rodou para este elemento...
		if(!foo.hasClass('peixe-ajax-file-upload-ready')){
			foo.addClass('peixe-ajax-file-upload-ready');
			//var required = ((foo.prop('required'))?('required'):(''));
			var name = foo.attr('name');
			var aux_name = name+"_aux";

			//acertando o nome do input para aux
			foo.attr('name', aux_name);

			//inserindo toda o container do upload
			$('<div class="peixe-ajax-upload-status"><input type="text" name="'+name+'" value="" style="display: none;"/><div class="upload-progress progress radius" style="display: none;"><span class="meter" style="width: 0%;"></span></div><div class="upload-sending font-14 margin-bottom" style="display: none;"><i class="fa-spinner fa-spin"></i> <span>Enviando...</span></div><div class="upload-success font-14 margin-bottom" style="display: none;"><i class="fa-check"></i> <span>Sucesso!</span></div><div class="upload-error font-14 alert-box radius alert" style="display: none;"><i class="fa-refresh pointer trigger-peixe-ajax-upload-retry right" title="Tentar novamente..."></i> <span>Erro ao enviar o arquivo</span></div><div class="upload-file-placeholder font-14 alert-box radius" style="display: none;"><i class="fa-file-text-o"></i> <span class="uploaded-file">nome-do-arquivo.php</span> <a href="#" style="color: #fff;" class="margin-bottom trigger-peixe-ajax-upload-remove" title="Remover este arquivo"><i class="fa-close right"></i></a></div></div>').insertAfter(foo.next('label'));

		}
	})
}

function peixeMediaQuery() {
	if(window.innerWidth < 768){
		return 'small';
	}
	else {
		return 'large';
	}
}

function peixeUpdateCurrentUrl(url) {
	peixe_current_url = url;
	//console.log(url);
	//window.history.pushState('', '', url);
}

function peixeInit() {
	peixeAddRequiredBullets();
	peixeAjaxFileUploadInit();
}

function peixeSmartSave(c) {
	//primeiro clica para finalizar o editor
	target = $('.ct-ignition__button--confirm:visible');
	if(target.length){
		target.trigger('click');
	}
	//depois dá um trigger no proprio form clicado ou no último botão de peixe-save
	if(c){
		return true;
	}
	else {
		target = $('.peixe-save:visible').last();
		if(target.length){
			target.trigger('click');
		}
	}
}

$(document).ready(function(){

	if(jQuery.hotkeys){
		jQuery.hotkeys.options.filterInputAcceptingElements = false;
		jQuery.hotkeys.options.filterContentEditable = false;
		jQuery.hotkeys.options.filterTextInputs = false;

		//hotkeys
		$(document).bind('keydown', 'ctrl+s', function(){
			peixeSmartSave();
			return false;
		});
	}

	peixeInit();

	//mostra mensagens
	showPeixeMessage();

	// tratando mensagens
	$(document).on('click', 'div.wrapper-message', function(){
		removePeixeMessage();
	});

	$(document).on('mouseenter', 'div.wrapper-message', function(){
		clearTimeout(peixe_message_timer);
	});

	$(document).on('mouseleave', 'div.wrapper-message', function(){
		setTimeout(function(){
			slidePeixeMessageUp();
		}, 3000);
	});

	//botao de submit que só funciona com javascript, e impede dupla submissão
	$(document).on('click', 'form .submitter', function(e){
		e.preventDefault();
		$(this).closest('form').submit();
	})

	//tira o destaque de erro dos inputs, quando mudados
	/*$(document).on('focus', 'form .error', function(){
		$(this).removeClass('error');
	})*/
	$(document).on('focus', '.item.validation-error input, .item.validation-error select, .item.validation-error textarea', function(){
		$(this).closest('.item.validation-error').removeClass('validation-error');
	});

	//fazendo upload por ajax no onChange dos inputs file
	$(document).on('change', '[peixe-ajax-file-upload]', function(){
		input = $(this);
		action = ((typeof input.data('action') != 'undefined')?(input.data('action')):('peixe-ajax-file-upload.php'));
		peixeAjaxFileUploadSingleFile(input.attr('id'), action, input.data());
	});

	//resetando o formulário quando dá algum erro de upload
	$(document).on('click', '.trigger-peixe-ajax-upload-retry', function(){
		peixeAjaxFileUploadRetry($(this).closest('.peixe-ajax-upload-status').prev('input[type="file"]').attr('id'));
	});

	//resetando o formulário quando o usuário remover o arquivo uploadedado
	$(document).on('click', 'input[type="file"][peixe-ajax-file-upload]', function(e){
		//e.preventDefault();
		if(e.ctrlKey){
			e.preventDefault();
			var ans = confirm("Tem certeza que deseja remover este arquivo?");
			if (ans==true) {
				input = $(this);
				peixeAjaxFileUploadRetry(input.attr('id'));
				input.trigger('fileRemoved', {})
			} 
		}
	});

	//confirmando uma ação, em um click em um link por exemplo.
	$(document).on('click', '.peixe-confirm', function(e){
		//e.preventDefault();
		clicado = $(this);
		var ans = confirm(clicado.data('confirm').replace(/\\n/g, '\n'));
		if (ans==true) {
			return true;
		} else {
			return false;
		}
	});

	$(document).on('click', '.stop-propagation', function(e){
		e.stopPropagation();
	});

	$(document).on('submit', 'form.peixe-json', function(){
		form = $(this);
		error = false;
		if(typeof form.data('confirm') != 'undefined' && $.trim(form.data('confirm')) != ''){
			var ans = confirm(form.data('confirm').replace(/\\n/g, '\n'));
			if (ans==true) {
				error = false;
			} else {
				error = true;
			}
		}
		if(!error){
			peixeJSON(
				form.attr('action'), 
				form.serialize(), 
				'', 
				(typeof form.attr('peixe-log') != 'undefined' ? true : false), 
				'post', 
				(typeof form.data('json_error_message') != 'undefined' ? form.data('json_error_message').replace(/\\n/g, '\n') : false),
				(typeof form.attr('peixe-silent') != 'undefined' ? 'silent' : null)
			);
		}
		return false;
	});

	$(document).on('click', 'a.peixe-json, button.peixe-json', function(e){
		e.preventDefault();
		clicado = $(this);
		error = false;
		if(typeof clicado.data('confirm') != 'undefined' && $.trim(clicado.data('confirm')) != ''){
			var ans = confirm(clicado.data('confirm').replace(/\\n/g, '\n'));
			if (ans==true) {
				error = false;
			} else {
				error = true;
			}
		}
		if(!error){
			peixeJSON(
				(clicado.data('url') ? clicado.data('url') : clicado.attr('href')),
				'', 
				(typeof clicado.data('callback') != 'undefined' ? clicado.data('callback') : ''), 
				(typeof clicado.attr('peixe-log') != 'undefined' ? true : false), 
				'get', 
				(typeof clicado.data('json_error_message') != 'undefined' ? clicado.data('json_error_message').replace(/\\n/g, '\n') : false),
				(typeof clicado.attr('peixe-silent') != 'undefined' ? 'silent' : null)
			);
		}
		return false;
	});

	//controlando seções de conteudo (tipo accordion)
	$(document).on('click', '.trigger-peixe-section', function(){
		clicado = $(this);
		section = clicado.closest('.peixe-section');
		if(section.hasClass('open')){
			section.removeClass('open').addClass('closed');
		} else {
			section.removeClass('closed').addClass('open');
		}
	});

	//controlando os small toggles
	$(document).on('click', '.trigger-small-toggle', function(){
		clicado = $(this);
		section = clicado.closest('.section-small-toggle');
		if(section.hasClass('shown')){
			section.removeClass('shown').addClass('closed');
		} else {
			section.removeClass('closed').addClass('shown');
		}
	});

	//alterando o current url para reloads e afins
	$(document).on('click', '.peixe-current-url', function(e){
		c = $(this);
		peixeUpdateCurrentUrl((c.attr('href'))?(c.attr('href')):((c.data('url'))?(c.data('url')):(document.URL)));
	});

	$(document).on('click', '[peixe-reload]', function(e){
		e.preventDefault();
		var c = $(this);
		if(c.attr('peixe-done')) var d = eval("("+c.attr('peixe-done')+")");
		peixeUpdateCurrentUrl(c.data('keep-url') ? keepUrl(c.data('keep-url')) : (c.attr('href'))?(c.attr('href')):((c.data('url'))?(c.data('url')):(document.URL)));
		peixeGet(peixe_current_url, function(d){
			d = $.parseHTML(d);
			c.attr('peixe-reload').split(',').forEach(function(v){
				peixeReload(v, d);
			})
		}, null, (c.attr('peixe-silent') ? 'silent' : ''), (typeof d == 'function' ? d : null))
	});

	$(document).on('click', '[peixe-push-state]', function(e){
		var c = $(this);
		history.pushState('', '', c.data('keep-url') ? keepUrl(c.data('keep-url')) : (c.attr('href'))?(c.attr('href')):((c.data('url'))?(c.data('url')):(document.URL)));
	});

	$(document).on('click', '.peixe-menu-item', function(){
		var c = $(this);
		var w = c.closest('.peixe-menu');
		var cl = w.attr('peixe-menu-active-class');
		if(!c.hasClass(cl)){
			w.find('.peixe-menu-item.'+cl).removeClass(cl);
			c.addClass(cl);
		}
	});
	
	$(document).on('click', '.peixe-save', function(){
		var c = $(this);
		peixeSmartSave(c);
		return true;
	});

	//colcando ajax loader e screen freezer
	$('body:not(:has(.peixe-ajax-loader))').prepend('<div class="peixe-ajax-loader"><i class="fa fa-spinner fa-spin"></i> <span>Carregando...</span></div>');
	$('body:not(:has(.peixe-screen-freezer))').prepend('<div class="peixe-screen-freezer"></div>');
	$('body:not(:has(.wrapper-message))').prepend('<div class="wrapper-message closed"></div>');

	//fade no body apos carregar os scripts
	$('body.unresolved').removeClass('unresolved');

}) //doc.ready