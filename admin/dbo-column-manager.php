<? require_once("header.php"); ?>
<script src="<?= DBO_URL ?>/plugins/jcrop_dbo/js/jquery.Jcrop.min.js"></script>
<link rel="stylesheet" href="<?= DBO_URL ?>/plugins/jcrop_dbo/css/jquery.Jcrop.css" type="text/css" />
<style>
	html, body { height: 100%; }
	.processing-time, .dbo-queries-number { display: none; }
	#main-wrap { height: 100%; }
	.peixe-ajax-loader { width: 60px; height: 60px; font-size: 30px; text-align: center; line-height: 50px; border-radius: 1000px; background-color: rgba(1,1,1,.8); top: 50%; left: 50%; margin-left: -30px; margin-top: -30px; }
	.peixe-ajax-loader span { display: none; }
	#columns-settings .columns { display: none; }
	#columns-settings.show-1 #wrapper-column-1 { display: block; }
	#columns-settings.show-2 #wrapper-column-1 { display: block; }
	#columns-settings.show-2 #wrapper-column-2 { display: block; }
	#columns-settings.show-3 #wrapper-column-1 { display: block; }
	#columns-settings.show-3 #wrapper-column-2 { display: block; }
	#columns-settings.show-3 #wrapper-column-3 { display: block; }
	#columns-settings.show-4 #wrapper-column-1 { display: block; }
	#columns-settings.show-4 #wrapper-column-2 { display: block; }
	#columns-settings.show-4 #wrapper-column-3 { display: block; }
	#columns-settings.show-4 #wrapper-column-4 { display: block; }
	#columns-settings.show-5 #wrapper-column-1 { display: block; }
	#columns-settings.show-5 #wrapper-column-2 { display: block; }
	#columns-settings.show-5 #wrapper-column-3 { display: block; }
	#columns-settings.show-5 #wrapper-column-4 { display: block; }
	#columns-settings.show-5 #wrapper-column-5 { display: block; }
	#columns-settings.show-6 #wrapper-column-1 { display: block; }
	#columns-settings.show-6 #wrapper-column-2 { display: block; }
	#columns-settings.show-6 #wrapper-column-3 { display: block; }
	#columns-settings.show-6 #wrapper-column-4 { display: block; }
	#columns-settings.show-6 #wrapper-column-5 { display: block; }
	#columns-settings.show-6 #wrapper-column-6 { display: block; }
</style>

<div id="dbo-column-manager">
	<div class="row collapse">
		<div class="small-10 columns">
			<span class="prefix label">Quantas colunas você quer inserir?</span>
		</div>
		<div class="small-2 columns"><input type="text" name="" id="number-of-columns" value="" autofocus class="text-center"/></div>
	</div>

	<div id="columns-settings" style="display: none;">
		<div class="row collapse">
			<div class="small-2 columns end" data-column="1" id="wrapper-column-1">
				<input type="text" name="" id="column-1" value="" class="text-center"/>
			</div>
			<div class="small-2 columns end" data-column="2" id="wrapper-column-2">
				<input type="text" name="" id="column-2" value="" class="text-center"/>
			</div>
			<div class="small-2 columns end" data-column="3" id="wrapper-column-3">
				<input type="text" name="" id="column-3" value="" class="text-center"/>
			</div>
			<div class="small-2 columns end" data-column="4" id="wrapper-column-4">
				<input type="text" name="" id="column-4" value="" class="text-center"/>
			</div>
			<div class="small-2 columns end" data-column="5" id="wrapper-column-5">
				<input type="text" name="" id="column-5" value="" class="text-center"/>
			</div>
			<div class="small-2 columns end" data-column="6" id="wrapper-column-6">
				<input type="text" name="" id="column-6" value="" class="text-center"/>
			</div>
		</div>
		
		<div class="helper arrow-top margin-bottom"><p class="no-margin-for-small">Digite a proporção das colunas nos campos acima. A soma deve ser exatamente 12.</p></div>

		<p class="no-margin-for-small text-right">
			<input type="button" name="" id="inserir-colunas" value="Inserir colunas" class="button radius secondary small"/>
		</p>
	</div>

</div>

<script>

	function resetColumns() {
		$('#columns-settings').removeClass().hide().find('input[type="text"]').each(function(){
			$(this).val('');
		})
	}

	function atualizaColuna(id_coluna, tamanho) {
		$('#wrapper-column-'+id_coluna).removeClass().addClass('small-'+tamanho+' columns end');
	}

	function inserirColunas() {
		
		//variaveis para montar as colunas

		html = '<div class="row">';

		spacer = tinymce.Env.ie && tinymce.Env.ie < 11 ? '' : '<br data-mce-bogus="1" />';

		$('#columns-settings input[type="text"]:visible').each(function(){
			html = html + '<div class="large-'+$(this).val()+' columns"><p>'+spacer+'</p></div>';
		})

		html = html + '</div>'

		parent.tinyMCE.activeEditor.insertContent(html);
		parent.tinyMCE.activeEditor.nodeChanged();
		parent.tinyMCE.activeEditor.windowManager.close();
	}

	$(document).ready(function(){

		$(document).on('keyup', '#number-of-columns', function(){
			var next_idx = $('input[type=text]').index(this) + 1;
			val = $(this).val();
			if(val % 1 === 0 && val >= 1 && val <= 6){
				setTimeout(function(){
					resetColumns();
					$('#columns-settings').removeClass().addClass('show-'+val).fadeIn('fast', function(){
						$('input[type=text]:eq(' + next_idx + ')').focus();
					});
				}, 250);
			}
			resetColumns();
		});

		$(document).on('keyup', 'input[id^="column-"]', function(){
			atualizaColuna($(this).closest('div').data('column'), $(this).val());
			var next_idx = $('input').index(this) + 1;
			if($('input:eq('+next_idx+')').is(':visible')){
				$('input:eq(' + next_idx + ')').focus();
			}
			else {
				$('#inserir-colunas').focus();
			}
		});

		$(document).on('focus', 'input[id^="column-"]', function(){
			$(this).val('');
		});

		$(document).on('click', '#inserir-colunas', function(){
			inserirColunas();
		});

	}) //doc.ready
</script>
<? require_once("footer.php"); ?>