<? require_once('lib/includes.php') ?>
<?
	auth();
	//clears the trash from the session.
	unset($_SESSION['dbomaker_modulos']);
	//creates the backup directory (if not there already)
	checkBackupDir();
?>
<!DOCTYPE HTML>
<html>
<html xmlns="http://www.w3.org/1999/xhtml">
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery-ui/jquery-ui.js"></script>
<script type="text/javascript" src="../../js/peixelaranja.js"></script>
<script type="text/javascript" src="../../js/jquery.hotkeys.js"></script>
<script type="text/javascript" src="../../js/jquery.tabbable.js"></script>
<head>
<title>DBO Maker - Assistente de Criação de Módulos</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<link rel="shortcut icon" href="images/favicon.ico">
<link rel="apple-touch-icon" href="apple-touch-icon.png">
<link href="styles.css" rel="stylesheet" type="text/css">
<link href="js/jquery-ui/jquery-ui.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="../../fonts/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="../../css/common.css">
<script>

//live draggable

var sort_drag = 'sort';

//live draggable
function liveDraggable(selector, options){
  jQuery(document).on("mouseover", selector, function(){
	  if (sort_drag == 'drag') {
		if (!jQuery(this).data("init")) {
		  jQuery(this).data("init", true);
		  jQuery(this).draggable(options);
		}
	  }
  });
}

//live draggable
function liveSortable(selector, options){
  jQuery(document).on("mouseover", selector, function(){
	  if (sort_drag == 'sort') {
		if (!jQuery(this).data("init")) {
		  jQuery(this).data("init", true);
		  jQuery(this).sortable(options);
		}
	  }
  });
}

//live droppable
function liveDroppable(selector, options){
  jQuery(document).on("mouseover", selector, function(){
	if (!jQuery(this).data("init")) {
	  jQuery(this).data("init", true);
	  jQuery(this).droppable(options);
	}
  });
}

//makes the modules droppable for elements
function modulesDroppable() {
	if (sort_drag == 'drag') {
		$('.module').droppable({
			accept: ".field, .module",
			activeClass: "module-droppable-active",
			hoverClass: "module-droppable-hover",
			drop: function(event, ui) {
				var dropped = ui.draggable;
				if(dropped.hasClass('module')) {
					ui.helper.hide();
					//alert('Criar FK do módulo '+dropped.attr('module')+' no módulo '+$(this).attr('module'));
				}
				else if(dropped.hasClass('field')) {
					ui.helper.hide();
					//alert('Copiar campo '+dropped.attr('field')+" do modulo "+dropped.attr('module')+" para o modulo "+$(this).attr('module'));
				}
			}
		});
	}
}

function refreshButtonNext(button_id)
{
	var botao = $(button_id+" .button-next");
	botao.find('span').html(botao.attr('original_value'));
	botao.css('opacity', 1);
}

function showMessage(message, callback)
{
	if(callback === undefined)
	{
		alert(message);
	}
	else
	{
		alert(message);
		callback();
	}
}

function init () {
	$('.dica').each(function(){
		var dica = $(this);
		if(dica.children().size() == 0)
		{
			dica.html("<div class='dica-handler'></div><div class='dica-content'>"+dica.html()+"</div>");
		}
	})
	checkFieldForm();
	modulesDroppable();
}

function ajaxLoad (target, url, callback)
{
	if(callback === undefined)
	{
		$(target).load(url);
	}
	else {
		console.log(url);
		$(target).load(url, callback);
	}
}

function checkFieldForm ()
{
	checkType();
	checkPk();
	checkPerfil();
	checkJoinTipo();
}

function checkPlugin ()
{
	var campo = $('.wrapper-field select[name=plugin_selector]');
	ajaxLoad('.wrapper-plugin-detail', 'actions.php?getPluginDetail='+campo.val());
}

function checkJoinModulo ()
{
	var modulo = $('.wrapper-field .join-modulo').val();
	var campo_chave = $('.wrapper-field .join-chave');
	var campo_valor = $('.wrapper-field .join-valor');
	var campo_order = $('.wrapper-field .join-order');

	ajaxLoad(campo_chave, 'actions.php?getOptionsModuleFields='+modulo);
	ajaxLoad(campo_valor, 'actions.php?getOptionsModuleFields='+modulo);
	ajaxLoad(campo_order, 'actions.php?getOptionsModuleFields='+modulo);
}

function checkJoinValor ()
{
	var campo_valor = $('.wrapper-field .join-valor');
	var campo_order = $('.wrapper-field .join-order');

	campo_order.val(campo_valor.val());
}

function checkJoinTipo ()
{
	var campo = $('.wrapper-field .join-tipo');
	if(campo.val() == 'multi-select' || campo.val() == 'multi-checkbox')
	{
		$('.wrapper-field .wrapper-join-nn').show();
	} else {
		$('.wrapper-field .wrapper-join-nn').hide();
	}
}

function checkType ()
{
/*	var campo = $('.wrapper-field select[name=type]');
	if(
		campo.val() == 'VARCHAR' ||
		campo.val() == 'FLOAT'
	)
	{
		campo.css('width', '49%');
		$('.wrapper-field input[name=mysql_size]').show();
	}
	else
	{
		campo.css('width', '100%');
		$('.wrapper-field input[name=mysql_size]').hide();
	}
	*/
}

function checkPk ()
{
	var campo = $('.wrapper-field select[name=pk]');
	if(campo.val() == 1)
	{
		//$('.wrapper-field select[name=type]').closest('.row').hide();
	} else {
		//$('.wrapper-field select[name=type]').closest('.row').show();
	}
}

function checkPerfil ()
{
	var todos = $('.wrapper-field input[name=perfil_todos]');
	var perfis = new Array();
	var checados = new Array();
	var i = 0;
	var j = 0;
	$('.wrapper-field .campo_perfil').each(function(){
		if($(this).is(':checked') == true)
		{
			checados[j] = $(this);
			j++;
		}
		perfis[i] = $(this);
		i++;
	})
	if(i == j && i > 0) //todos checados
	{
		todos.attr('checked', 'checked');
	}
	if(todos.is(':checked'))
	{
		$(checados).each(function(){
			$(this).removeAttr('checked');
		})
		todos.closest('.input').find('.perfil').hide();
	}
	else {
		todos.closest('.input').find('.perfil').show();
	}
}

function checkTipo ()
{
	var tipo = $('.wrapper-field select[name=tipo]');
	ajaxLoad('.field-type-details', 'actions.php?getFieldTypeDetail='+tipo.val(), checkFieldForm);
}

$(document).ready(function(){


	$(document).bind('keydown', 'ctrl+s', function(){
		var butts = $('.button-salvar:visible');
		if(butts.length){
			butts.first().trigger('click');
		}
		else {
			$('.button-sync').trigger('click');
		}
		return false;
	});

	$(document).bind('keydown', 'ctrl+b', function(){
		$('.button-sync-db').trigger('click');
		return false;
	});

	$(document).bind('keydown', 'ctrl+n', function(){
		console.log('new');
		return false;
	});

	$(document).bind('keydown', 'ctrl+m', function(){
		$('#button-novo-modulo').trigger('click');
	});

	//logica para navegar pelo maker com teclado.. teclas de up/down
	$(document).bind('keydown', 'down', function(){
		ele = document.activeElement;
		node = ele.nodeName;
		//esta em um elemento pai de fieldset
		if(node == 'H2' || node == 'A'){
			$.tabNext();
			return false;
		}
	});

	$(document).bind('keydown', 'up', function(){
		ele = document.activeElement;
		node = ele.nodeName;
		//esta em um elemento pai de fieldset
		if(node == 'H2' || node == 'A'){
			$.tabPrev();
			return false;
		}
	});

	/* troca de seções dos módulos */
	$(document).bind('keydown', 'ctrl+down', function(){
		ele = document.activeElement;
		node = ele.nodeName;
		//esta em um elemento pai de fieldset
		$(document.activeElement).closest('.fieldset').next('.fieldset').find('h2').first().focus();
		return false;
	});

	$(document).bind('keydown', 'ctrl+up', function(){
		ele = document.activeElement;
		node = ele.nodeName;
		//esta em um elemento pai de fieldset
		$(document.activeElement).closest('.fieldset').prev('.fieldset').find('h2').first().focus();
		return false;
	});

	$(document).bind('keydown', 'return', function(){
		ele = document.activeElement;
		node = ele.nodeName;
		//esta em um elemento pai de fieldset
		if(node == 'H2'){
			$(ele).trigger('click');
		}
		else if(ele.type == 'radio' && ele.name == 'tipo'){
			$('#trigger-form-new-field').trigger('click');
		}
		else if(ele.type == 'text'){
			var button_salvar = $(ele).closest('.wrapper-box').find('.button-salvar:visible');
			if(button_salvar.length){
				button_salvar.trigger('click');
			}
		}
		else if($(ele).hasClass('button-new') || $(ele).hasClass('button-next')){
			$(ele).trigger('click');
		}
	});

	$(document).bind('keydown', 'ctrl+return', function(){
		ele = document.activeElement;
		node = ele.nodeName;
		//esta em um elemento pai de fieldset
		if(node == 'TEXTAREA'){
			$(ele).trigger('dblclick');
		}
	});

	$(document).bind('keydown', 'pagedown', function(){
		ele = document.activeElement;
		node = ele.nodeName;
		//esta em um elemento pai de fieldset
		if(node == 'TEXTAREA'){
			$(ele).trigger('dblclick');
		}
	});

	$(document).bind('keydown', 'ctrl+d', function(){
		$('.new-field:visible').trigger('click');
		return false;
	});

	jQuery.hotkeys.options.filterInputAcceptingElements = false;
	jQuery.hotkeys.options.filterContentEditable = false;
	jQuery.hotkeys.options.filterTextInputs = false;

	$('#wrapper-modules > .anchor').load('actions.php?getDiskModules=1<?= $_GET[all_modules] ? "&all_modules=1" : "" ?>', init);

	$(document).on('click', '#wrapper-modules > .anchor a', function(e){
		e.preventDefault();
		if($(this).hasClass('locked')) {
			alert('Sem permissão de escrita');
		}
		else {
			$('#wrapper-modules > .anchor a').removeClass('active');
			$(this).addClass('active');
			ajaxLoad('#wrapper-fields', 'actions.php?showModule='+$(this).attr('href'), function(){
				$('#h2-module-basic-info').focus();
				init();
			});
		}
	})

	//module details accordions
	$(document).on('click', '.wrapper-module .fieldset h2', function(e){

		if($(this).hasClass('active'))
		{
			$(this).removeClass('active');
			$(this).closest('.fieldset').find('.anchor').hide();
		}
		else
		{
			$('.wrapper-module .fieldset h2').removeClass('active');
			$('.wrapper-module .fieldset .anchor').hide();

			$(this).addClass('active');
			$(this).closest('.fieldset').find('.anchor').fadeIn(100);
		}
	})

	//field details accordions
	$(document).on('click', '.wrapper-field .fieldset h2', function(e){
		if($(this).hasClass('active'))
		{
			$(this).removeClass('active');
			$(this).closest('.fieldset').find('.anchor').hide();
		}
		else
		{
			$(this).addClass('active');
			$(this).closest('.fieldset').find('.anchor').fadeIn(100);
		}
	})

	//fazendo as textareas expandirem sob demanda
	$(document).on('focus', 'textarea.code', function(){
		var textarea = $(this);
		textarea.closest('.anchor').find('textarea.code').attr('rows', '1');
		textarea.attr('rows', '20');
	})

	//fazendo as textareas expandirem sob demanda
	$(document).on('dblclick', 'textarea.code', function(){
		var ww = $(window).width();
		var wh = $(window).height();
		$(this).toggleClass('code-full');
		if($(this).hasClass('code-full'))
		{
			$(this).css('width', eval(ww-40)+"px");
			$(this).css('height', eval(wh-40)+"px");
			$(this).css('z-index', '1000');
		} else {
			$(this).css('width', '100%');
			$(this).css('height', 'auto');
			$(this).css('z-index', '0');
		}
		$(this).focus();
	})

	//making TAB tabulate
	$(document).on('keydown', 'textarea.code-full', function(e){
		if(e.keyCode == 9)
		{
			e.preventDefault();
			insertAtCaret(this, '\t');
		}
	})

	//para inserir os tabs no esquema
	function insertAtCaret(element, text) {
		if (document.selection) {
			element.focus();
			var sel = document.selection.createRange();
			sel.text = text;
			element.focus();
		} else if (element.selectionStart || element.selectionStart === 0) {
			var startPos = element.selectionStart;
			var endPos = element.selectionEnd;
			var scrollTop = element.scrollTop;
			element.value = element.value.substring(0, startPos) + text + element.value.substring(endPos, element.value.length);
			element.focus();
			element.selectionStart = startPos + text.length;
			element.selectionEnd = startPos + text.length;
			element.scrollTop = scrollTop;
		} else {
			element.value += text;
			element.focus();
		}
	}

	//clicks on fields names - controls and access
	$(document).on('click', '#menu-fields .anchor a', function(e){
		e.preventDefault();
		target = e.target;
		if($(target).closest('ul').hasClass('controls'))
		{
			var partes = $(this).attr('href').split('||');
			var mod = partes[0];
			var field = partes[1];
			var attr = $(target).attr('rel')
			ajaxLoad($(target).closest('.controls'), 'actions.php?toggleFieldControl='+mod+'||'+field+'||'+attr, function(response){
				$('.wrapper-field.module-'+mod+'.field-'+field+' .controls').html(response);
			});
		} else {
			$('#menu-fields > .anchor a').removeClass('active');
			$(this).addClass('active');
			ajaxLoad('#wrapper-details', 'actions.php?showField='+$(this).attr('href'), init);
		}
	})

	//clicks on field details controls
	$(document).on('click', '.wrapper-field ul.controls li', function(){
		var partes = $(this).closest('.controls').attr('rel').split('||');
		var mod = partes[0];
		var field = partes[1];
		var attr = $(this).attr('rel');
		var url = 'actions.php?toggleFieldControl='+mod+"||"+field+"||"+attr;
		ajaxLoad($(this).closest('.controls'), url, function(response){
			$('.module-'+mod+' .field-'+field+' .controls').html(response);
		});
	})

	init();

	//copiando nome do modulo no plural
	$(document).on('blur', '#module-main input[name=titulo]', function(){
		var valor = $(this).val();
		$('#module-main input[name=titulo_plural]').val(valor+'s');
	})

	$(document).on('blur', '#module-main input[name=modulo]', function(){
		var valor = $(this).val();
		var campo = $('#module-main input[name=tabela]');
		if(campo.val().trim() == ''){
			campo.val(valor);
		}
	})

	//self-delete do wrapper-field-type-detail
	$(document).on('click', '.wrapper-field-type-detail .self-delete', function(e){
		e.preventDefault();
		$(this).closest('.wrapper-field-type-detail').remove();
	})

	//adição de nova imagem
	$(document).on('click', '.wrapper-field .image-new-size', function(e){
		e.preventDefault();
		var botao = $(this);
		var ultimo = 0;
		$(this).closest('.row').find('.dbo-image-array').each(function(){
			ultimo = $(this).attr('rel');
		});
		ultimo++;
		$('.lixo').load('actions.php?getFieldImageDetail='+ultimo, function(response){
			botao.closest('.input').append(response);
		});
	})

	//toggle controls - controles em geral
	$(document).on('click', '.toggle-control', function(){
		ajaxLoad('.lixo', 'actions.php?toggleControl='+$(this).attr('rel'));
	})

	//chama o formulario de novo campo
	$(document).on('click', '.new-field', function(){
		$('#wrapper-fields .anchor a').removeClass('active');
		ajaxLoad('#wrapper-details', 'actions.php?getNewFieldForm='+$(this).attr('rel'), function(){
			$('#tipo-text').focus();
		});
	})

	//clicks nos tipos de campo
	$(document).on('mousedown', '.field-types li input', function(){
		$(this).trigger('click');
		$('#trigger-form-new-field').trigger('click');
	})
	$(document).on('mousedown', '.field-types li input + label', function(){
		$(this).trigger('click');
		$('#trigger-form-new-field').trigger('click');
	})

	//fazendo os botões de salvar aparecerem na hora certa...
	$(document).on('input change', '#form-field input, #form-field textarea, #form-field select', function(){
		if($('#wrapper-details .button-next').is(':hidden'))
		{
			$('#wrapper-details .button-next').fadeIn(100);
		}
	})
	$(document).on('input change', '#form-module input, #form-module textarea, #form-module select', function(){
		if($('#wrapper-fields .button-next').is(':hidden'))
		{
			$('#wrapper-fields .button-next').fadeIn(100);
		}
	})

	//botoes de salvar dos formulários
	$(document).on('click', '.button-next', function(){
		var previous_value = $(this).children('span').html();
		var sending_value = $(this).attr('sending');
		if(previous_value != sending_value)
		{
			$(this).children('span').html(sending_value);
			$(this).css('opacity', '0.7');

			var form = $(this).attr('rel');
			$.post(
				$(form).attr('action'),
				$(form).serialize(),
				function(data){
					var partes = data.split('::');
					//updated a field successfully
					if(partes[0] == 'RUN_UPDATE_FIELD_OK')
					{
						ajaxLoad('#wrapper-fields', 'actions.php?showModule='+partes[1], function(){
							$('#wrapper-fields .field-'+partes[2]).fadeTo(100, 0, function(){
								$('#wrapper-fields .field-'+partes[2]).addClass('active');
								$('#wrapper-fields .field-'+partes[2]).fadeTo(100, 1);
								ajaxLoad('#wrapper-details', 'actions.php?showField='+partes[1]+'||'+partes[2], function(){
									init();
									$('#button-new-field').focus();
								});
							});
						});
					//updated a field successfully
					}else if(partes[0] == 'RUN_UPDATE_MODULE_OK') {
						ajaxLoad('#wrapper-modules > .anchor', 'actions.php?showModules=1', function(){
							$('#wrapper-modules .module-'+partes[1]).fadeTo(100, 0, function(){
								$('#wrapper-modules .module-'+partes[1]).addClass('active');
								$('#wrapper-modules .module-'+partes[1]).fadeTo(100, 1);
								ajaxLoad('#wrapper-fields', 'actions.php?showModule='+partes[1], function(){
									init();
									$('#button-new-field').focus();
								});
							});
						});
					//created a blank new field successfully
					}else if(partes[0] == 'RUN_NEW_FIELD_OK') {
						ajaxLoad('#wrapper-fields', 'actions.php?showModule='+partes[1], function(){
							$('#wrapper-fields .field-'+partes[2]).fadeTo(100, 0, function(){
								$('#wrapper-fields .field-'+partes[2]).addClass('active');
								$('#wrapper-fields .field-'+partes[2]).fadeTo(100, 1);
								ajaxLoad('#wrapper-details', 'actions.php?showField='+partes[1]+'||'+partes[2], function(){
									init();
									$('#wrapper-details input[name=titulo]').focus();
									$('#wrapper-details .button-next').fadeIn(100);
								});
							});
						});
					}else if(partes[0] == 'ERROR') {
						showMessage(partes[1]);
						refreshButtonNext(partes[2]);
					}else{
						alert(data);
					}
				}
			);
		//if the guy tries to click twice... or more!
		} else {
			showMessage('Enviando... por favor aguarde.');
		}

	})//button next

	//disable click default on buttons
	$(document).on('click', '.button, .button-inactive', function(e){
		e.preventDefault();
	})

	//alternate types of module buttons
	$(document).on('click', '.toggle-module-button-view', function(){
		var button = $(this);
		if(button.closest('.wrapper-module-button').find('.standard-type').is(':visible'))
		{
			button.closest('.wrapper-module-button').find('.standard-type').hide();
			button.closest('.wrapper-module-button').find('.custom-type').show();
			button.closest('.wrapper-module-button').find('input.custom-flag').val(1);
		}
		else if(button.closest('.wrapper-module-button').find('.custom-type').is(':visible'))
		{
			button.closest('.wrapper-module-button').find('.custom-type').hide();
			button.closest('.wrapper-module-button').find('.standard-type').show();
			button.closest('.wrapper-module-button').find('input.custom-flag').val(0);
		}
	})

	//adding a new button
	$(document).on('click', '.new-module-button', function(e){
		var botao = $(this);
		var ultimo = 0;
		$(this).closest('.wrapper-module-buttons').find('.wrapper-module-button').each(function(){
			ultimo = $(this).attr('posicao');
		});
		ultimo++;
		$('.lixo').load('actions.php?getModuleButtonForm='+ultimo, function(response){
			$('.wrapper-module-buttons').append(response);
		});
	})

	//removing buttons from DOM
	$(document).on('click', '.remove-button', function(){
		$(this).closest('.wrapper-module-button').remove();
	})

	//creating a new module
	$(document).on('click', '.new-module', function(e){
		e.preventDefault();
		ajaxLoad('#wrapper-fields', 'actions.php?runNewModule=1', function(){
			init();
			$('#h2-module-basic-info').addClass('active');
			$('#anchor-module-basic-info').fadeIn(100, function(){
				$('#wrapper-fields input[name=titulo]').focus();
			})
			$('#wrapper-details').html('');
		});
	})

	//funcoes especificas dos detalhes do campo
	$(document).on('change', '.wrapper-field select[name=type]', checkFieldForm);
	$(document).on('change', '.wrapper-field select[name=pk]', checkFieldForm);
	$(document).on('change', '.wrapper-field input[name^=perfil]', checkFieldForm);
	$(document).on('change', '.wrapper-field select[name=tipo]', checkTipo);
	$(document).on('change', '.wrapper-field .join-modulo', checkJoinModulo);
	$(document).on('change', '.wrapper-field .join-valor', checkJoinValor);
	$(document).on('change', '.wrapper-field .join-tipo', checkFieldForm);
	$(document).on('change', '.wrapper-field select[name=plugin_selector]', checkPlugin);

	/* toolbar! */

	//button to sync all modules to disk
	$(document).on('click', '.button-sync-all', function(){
		showPeixeLoader();
		ajaxLoad('.lixo', 'actions.php?syncAll=1', function(response){
			setPeixeMessage('<div class="success"><strong><u>Todos os módulos</u></strong> sincronizados com sucesso!</div>');
			showPeixeMessage();
			hidePeixeLoader();
		});
	})

	//button to sync all updated modules
	$(document).on('click', '.button-sync', function(){
		showPeixeLoader();
		ajaxLoad('.lixo', 'actions.php?syncUpdated=1', function(r){
			setPeixeMessage('<div class="success"><strong><u>Módulos</u></strong> sincronizados com sucesso!</div>');
			showPeixeMessage();
			hidePeixeLoader();
		});
	})

	//button to sync all updated modules
	$(document).on('click', '.button-sync-db', function(){
		showPeixeLoader();
		ajaxLoad('.lixo', 'actions.php?syncDatabase=1', function(r){
			setPeixeMessage('<div class="success"><u><strong>Banco</strong></u> sincronizaco com sucesso: '+(r.length ? r : 'nenhuma alteração.')+'</div>');
			showPeixeMessage();
			hidePeixeLoader();
		});
	})

	//sort or drag...
	$(document).on('click', '.button-enable-dnd', function(){
		$('.sortable.ui-sortable').sortable("destroy");
		$('.sortable').data("init", false);
		sort_drag = 'drag';
		modulesDroppable();
	})
	$(document).on('click', '.button-enable-sort', function(){
		$('.draggable.ui-draggable').draggable("destroy");
		$('.draggable').data("init", false);
		sort_drag = 'sort';
		$('.module.ui-droppable').droppable('destroy');
	})

	$('.sortable, .draggable').disableSelection();

	liveDraggable('.draggable', {
		distance: 15,
		revert: true,
		helper: 'clone',
		scroll: false,
		cursor: 'pointer',
		cursorAt: {
			left: 5,
			bottom: 10
		},
	});

	liveSortable('.sortable', {
		distance: 15,
		placeholder: "sort-place-holder",
		axis: 'y',
		stop: function(i){
			var this_one = $(this);
			//module sorting
			if (this_one.hasClass('sort-modules'))
			{
				$.ajax({
					type: 'GET',
					url: 'actions.php?sortModules=1',
					data: this_one.sortable('serialize'),
					//success: function(data){ alert(data); }
				})
			}
			//field sorting
			else if(this_one.hasClass('sort-fields'))
			{
				$.ajax({
					type: 'GET',
					url: 'actions.php?sortFields=1&module='+this_one.attr('module'),
					data: this_one.sortable('serialize'),
					//success: function(data){ alert(data); }
				})
			}
		}
	});

	$('.trash').droppable({
		accept: ".module, .field",
		activeClass: "trash-active",
		hoverClass: "trash-hover",
		drop: function(event, ui) {
			var lixo = ui.draggable;
			if(lixo.hasClass('module')) {
				ui.helper.hide();
				ajaxLoad('.lixo', 'actions.php?deleteModule='+lixo.attr('module'), function(resp){
					lixo.addClass('deleting');
					lixo.fadeOut();
				})
			}
			else if(lixo.hasClass('field')) {
				ui.helper.hide();
				ajaxLoad('.lixo', 'actions.php?deleteField=1&module='+lixo.attr('module')+'&field='+lixo.attr('field'), function(r){
					lixo.addClass('deleting');
					lixo.fadeOut();
				})
			}
		}
	});

})// doc ready

</script>
</head>
<body>