<?php

	function paginaForm($pag, $params = array())
	{

		global $_pagina;

		$_pagina = $pag;

		require_once(DBO_PATH.'/core/dbo-ui.php');
		global $hooks;
		global $_pes;
		global $_system;

		if($pag->getEditorType() == 'tinymce')
		{
			$iniciar_editor = meta::getPreference('editor_type') == 'codigo' ? false : true;
			?>
			<script>
				var iniciar_editor = <?= $iniciar_editor ? 'true' : 'false' ?>;
			
				function trocaEditor() {
					if(iniciar_editor == false){
						editorInit();
						iniciar_editor = true;
						$('#ed_toolbar_texto').hide();
					}
					else {
						var ed = tinymce.activeEditor;
						if(ed && ed.isHidden()){
							val = window.dboEditor.dboAutop($('#texto').val());
							ed.show();
							ed.setContent(val);
							$('#ed_toolbar_texto').hide();
						}
						else {
							mce_container = $(ed.getContainer());
							code_container = $('#texto');
							code_container.height(mce_container.height()-100);
							$('#ed_toolbar_texto').show();
							ed.hide();
						}
					}
				}

			</script>
			<?php
		}

		extract($params);

		$operation = $pag->id ? 'update' : 'insert';

		ob_start();

		echo dboImportJs(array(
			'scrolllock',
			'quicktags',
			'hotkeys',
		));

		?>
		<style>
			.wrapper-pub-option { padding-bottom: 5px; display: none; }
			.wrapper-pub-option input, 
			.wrapper-pub-option select { margin-bottom: 5px; }
			#wrapper-categorias-da-pagina ul li ul { margin-left: 20px; }
			#wrapper-categorias-da-pagina input[type="checkbox"] { margin-bottom: 0px; padding-bottom: 8px; }
		</style>

		<form id="form-pagina" method="post" action="<?= secureUrl('dbo/core/dbo-pagina-ajax.php?action=salvar-pagina&tipo='.$tipo.'&pagina_id='.$pag->id."&full_url=".dboEncode($pag->keepUrl())) ?>" peixe-silent>
			<div class="row almost full">
				<div class="large-9 columns">
					<div class="row">
						<div class="large-12 columns">
							<h3><?= ($pag->status != 'rascunho-automatico' ? 'Editar' : 'Adicionar nov'.$genero)." ".$titulo ?></h3>
						</div>
					</div>
					
					<? $hooks->do_action('dbo_'.$tipo.'_form_titulo_before', $pag, $params); ?>
			
					<div class="margin-bottom">
						<div class="row" id="wrapper-titulo">
							<div class="large-12 columns">
								<input type="text" name="titulo" id="pagina-titulo" data-generate_slug="<?= $pag->status == 'rascunho-automatico' ? 'true' : 'false' ?>" value="<?= $pag->titulo != '(sem título)' ? htmlSpecialChars($pag->titulo) : '' ?>" placeholder="Digite aqui o título" autofocus class="font-20" style="margin-bottom: 5px;"/>
							</div>
						</div>
						<div class="row wrapper-pagina-field-slug" style="<?= $pag->status == 'rascunho-automatico' ? 'opacity: 0;' : '' ?> <?= $pag->hideFormField('slug') ? 'display: none;' : '' ?>" id="wrapper-pagina-slug">
							<div class="large-12 columns">
								<div class="font-12">
									<span class="color medium">Link permanente: <?= SITE_URL.($pag->slugPrefix() ? '/'.$pag->slugPrefix() : '') ?>/</span><span id="wrapper-slug-view"><strong id="slug-label" class="color" style="padding-right: 7px;"><?= $pag->slug ?></strong><span class="button radius secondary no-margin tiny font-10 trigger-slug-edit">EDITAR</span></span><span id="wrapper-slug-edit" style="display: none;"><input type="text" name="slug" id="pagina-slug" value="<?= $pag->slug ?>" data-slug_atual="<?= $pag->slug ?>" style="width: auto; display: inline-block; height: 21px;" class="no-margin"/> <span class="button radius secondary no-margin tiny font-10 trigger-slug-save">SALVAR</span> <a href="" class="underline trigger-slug-edit" style="position: relative; left: 5px;">cancelar</a></span>
								</div>
							</div>
						</div>
					</div>

					<? $hooks->do_action('dbo_'.$tipo.'_form_titulo_after', $pag, $params); ?>
					
					<? $hooks->do_action('dbo_'.$tipo.'_form_subtitulo_before', $pag, $params); ?>

					<div class="row wrapper-pagina-field-subtitulo" id="wrapper-subtitulo" style="<?= $pag->hideFormField('subtitulo') ? 'display: none;' : '' ?>">
						<div class="large-12 columns">
							<?= $pag->getFormElement($operation, 'subtitulo', array(
								'placeholder' => 'Digite aqui o subtítulo',
								'styles' => 'margin-top: 5px; margin-bottom: 1em',
							)); ?>
						</div>
					</div>

					<? $hooks->do_action('dbo_'.$tipo.'_form_subtitulo_after', $pag, $params); ?>
					
					<? $hooks->do_action('dbo_'.$tipo.'_form_resumo_before', $pag, $params); ?>

					<?php
						if($tipo != 'pagina')
						{
							?>
							<div class="row wrapper-pagina-field-resumo" id="wrapper-resumo" style="<?= $pag->hideFormField('resumo') ? 'display: none;' : '' ?>">
								<div class="large-12 columns">
									<?= $pag->getFormElement($operation, 'resumo', array(
										'placeholder' => 'Digite aqui o resumo',
										'styles' => 'margin-top: 5px; margin-bottom: 1em',
									)); ?>
								</div>
							</div>
							<?php
						}
					?>
			
					<? $hooks->do_action('dbo_'.$tipo.'_form_resumo_after', $pag, $params); ?>
					
					<? $hooks->do_action('dbo_'.$tipo.'_form_conteudo_before', $pag, $params); ?>
			
					<?php
						/* ------------------------------------------------------------ */
						/* CONTENT TOOLS ---------------------------------------------- */
						/* ------------------------------------------------------------ */
						if($pag->getEditorType() == 'content-tools')
						{
							?>
							<div class="input input-content-tools wrapper-pagina-field-texto" style="min-height: <?= $pag->tipo == 'pagina' ? 500 : 200 ?>px; <?= $pag->hideFormField('texto') ? 'display: none;' : '' ?>">
								<div>
								<?= dboUI::field('content-tools', 'update', $pag, array(
									'name' => 'texto',
									'value' => $pag->texto,
									'params' => array(
										'template' => $pag->getTemplate(),
									),
								)); ?>
								</div>
							</div>
							<?php
						}
						/* ------------------------------------------------------------ */
						/* TINYMCE ---------------------------------------------------- */
						/* ------------------------------------------------------------ */
						elseif($pag->getEditorType() == 'tinymce')
						{
							?>
							<div style="<?= $pag->hideFormField('texto') ? 'display: none;' : '' ?>" class="wrapper-pagina-field-texto">
								<div class="row">
									<div class="large-6 columns">
										<span class="ed_button font-14 trigger-colorbox-modal" data-width="100%" data-height="100%" data-url="dbo-media-manager.php?dbo_modal=1&modulo=pagina&modulo_id=<?= $pag->id ?>&destiny=tinymce&external_button=1" data-transition="none" data-fadeout="1"><i class="fa fa-fw fa-image top-1"></i> Adicionar mídia</span>
									</div>
									<div class="large-6 columns">
										<dl class="sub-nav right no-margin top-9">
											<dd class="<?= $iniciar_editor ? 'active' : '' ?>"><a href="#" tabindex="-1" class="trigger-editor-visual" data-dbo-set-pref data-pref_key="editor_type" data-pref_value="visual">Visual</a></dd>
											<dd class="<?= $iniciar_editor ? '' : 'active' ?>"><a href="#" tabindex="-1" class="trigger-editor-codigo" data-dbo-set-pref data-pref_key="editor_type" data-pref_value="codigo">Código</a></dd>
										</dl>
									</div>
								</div>
											
								<div class="row">
									<div class="large-12 columns">
										<script>edToolbar('texto', {
											styles: (!iniciar_editor ? '' : 'display: none;'),
										})
										</script>
										<?= $pag->getFormElement($operation, 'texto', array(
											'classes' => 'editor code-editor',
											'styles' => ($iniciar_editor ? 'height: 300px; opacity: 0;' : 'height: 600px;'),
											'input_id' => 'texto',
											//'edit_function' => ($iniciar_editor ? 'dboAutop' : null),
											'init_js' => false,
										)) ?>
										<textarea name="texto_codigo" id="texto-codigo" spellcheck='false' class="code-editor" style="display: none;"></textarea>
									</div>
								</div>
							</div>
							<?php
						}
					?>

			
					<? $hooks->do_action('dbo_'.$tipo.'_form_conteudo_after', $pag, $params); ?>
					
					<?php
						if($extension_module)
						{
							?>
							<div class="form-<?= $operation ?>">
								<?php

								$hooks->do_action('dbo_'.$tipo.'_form_extension_module_before', $pag, $params);

								$pag->ext_mod = new $extension_module();
								
								if($operation == 'insert')
								{
									$pag->ext_mod->getInsertForm(array('fields_only' => true));
								}
								elseif($operation == 'update')
								{
									$pag->ext_mod->id = $pag->id;
									$pag->ext_mod->load();
									$pag->ext_mod->getUpdateForm(array('fields_only' => true));
								}

								$hooks->do_action('dbo_'.$tipo.'_form_extension_module_after', $pag, $params);

								?>
							</div>

							<div class="row">
								<div class="large-12 columns">
									<hr>
								</div>
							</div>
							<?php
						}
					?>

					<?php
						//mostra os content blocks específicos para esta slug
						if(is_array($_system['content_block']['pagina']['slug'][$pag->slug]))
						{
							?>
							<div class="row">
								<?php
									foreach($_system['content_block']['pagina']['slug'][$pag->slug] as $block_name => $block)
									{
										$block['modulo'] = 'pagina';
										$block['modulo_id'] = $pag->slug;
										$block['name'] = $block_name;
										?>
										<div class="large-<?= $block['grid'] ?> columns end">
											<?= dbo_content_block::renderField($block) ?>
										</div>
										<?php
									}
									unset($block);
								?>
								<div class="large-12 columns">
									<hr>
								</div>
							</div>
							<?php
						}						
					?>

					<? $hooks->do_action('dbo_'.$tipo.'_form_autor_before', $pag, $params); ?>

					<div class="row">
						<?
							if(hasPermission('admin', 'pagina-'.$tipo))
							{
								?>
								<div class="large-6 columns wrapper-pagina-field-autor" style="<?= $pag->hideFormField('autor') ? 'display: none;' : '' ?>">
									<label for="">Autor desta publicação</label>
									<div id="wrapper-autor">
										<?
											if(!$pag->autor)
											{
												echo $pag->getFormElement($operation, 'autor', array(
													'join_label' => $_pes->nome,
													'value' => loggedUser(),
													'required' => true
												));	
											}
											else
											{
												echo $pag->getFormElement($operation, 'autor', array(
													'required' => true
												));	
											}
										?>
									</div>
								</div>
								<?
							}
						?>
						<div class="large-6 columns">
							<? $hooks->do_action('dbo_'.$tipo.'_form_autor_sibling', $pag, $params); ?>
						</div>
					</div>

					<? $hooks->do_action('dbo_'.$tipo.'_form_autor_after', $pag, $params); ?>
				</div>
				<div class="large-3 columns">
					<? $hooks->do_action('dbo_'.$tipo.'_form_sidebar_before', $pag, $params); ?>
					
					<div id="pagina-controles">
			
						<? $hooks->do_action('dbo_'.$tipo.'_form_sidebar_prepend', $pag, $params); ?>
			
						<div class="panel font-13 radius">
							<div class="row">
								<div class="large-12 columns">
									<strong>Publicação</strong><br />
									<div id="wrapper-publicacao">
										<hr class="small">
										<span data-status="rascunho" class="button secondary small radius trigger-form-submit">Salvar como <span id="button-rascunho-term"><?= $pag->status == 'pendente' ? 'pendente' : 'rascunho' ?></span></span><br />
										<?
											$status_name = $pag->getValue('status', $pag->status);
										?>
										<p class="no-margin">
											<i class="fa fa-thumb-tack fa-fw font-14"></i> Status: <strong><?= $status_name ? $status_name : 'Não salvo' ?></strong> <a href="" class="underline trigger-pub-option" style="display: none;">Editar</a>
										</p>
										<div class="wrapper-pub-option" style="display: none;">
											<div class="row">
												<div class="large-12 columns">
													<select id="status-selector" class="pub-option">
														<? 
															if($pag->status == 'publicado')
															{
																?>
																<option value="publicado" selected>Publicado</option>
																<?
															}
															elseif($pag->status == 'agendado')
															{
																?>
																<option value="agendado" selected>Agendado</option>
																<?
															}
														?>
														<option value="pendente" <?= $pag->status == 'pendente' ? 'selected' : '' ?> data-button-titulo="pendente">Revisão pendente</option>
														<option value="rascunho" <?= $pag->status == 'rascunho' || !$pag->status ? 'selected' : '' ?> data-button-titulo="rascunho">Rascunho</option>
													</select>
													<a href="" class="trigger-cancel-pub-option underline margin-bottom">cancelar</a>
												</div>
											</div>
										</div>
										<p class="no-margin" style="display: none;">
											<i class="fa fa-eye fa-fw font-14"></i> Visibilidade: <strong>Público</strong> <a href="" class="underline" style="display: none;">Editar</a>
										</p>
										<div class="wrapper-pub-option"></div>
										<p class="no-margin">
											<i class="fa fa-calendar fa-fw font-14"></i>
											Data: <strong id="wrapper-data-publicacao"><?= $pag->data ? dboDate('j/M/Y H:i', strtotime($pag->data)) : 'Agora' ?></strong> <a href="" class="underline trigger-pub-option">Editar</a>
										</p>
										<div class="wrapper-pub-option item" style="padding-top: 5px; display: none;">
											<div class="row">
												<div class="large-12 columns">
													<?= $pag->getFormElement($operation, 'data', array(
														'input_id' => 'data-publicacao',
														'placeholder' => 'Selecione a data',
													)) ?>
													<a href="" class="trigger-cancel-pub-option underline margin-bottom">cancelar</a>
												</div>
											</div>
										</div>
										<hr class="small">
										<div class="row" id="">
											<div class="large-6 columns">
												<?
													if($pag->status != 'rascunho-automatico' && $pag->status != 'lixeira')
													{
														?>
														<a href="<?= secureUrl('dbo/core/dbo-pagina-ajax.php?action=lixeira-from-form&pagina_id='.$pag->id.'&'.CSRFVar()) ?>" class="top-9 peixe-json" data-confirm="Tem certeza que deseja enviar este item para a lixeira?"><i class="fa fa-trash font-14 fa-fw"></i> Lixeira</a>
														<?
													}
												?>
											</div>
											<div class="large-6 columns text-right">
												<?
													if(!$pag->status || $pag->status == 'rascunho' || $pag->status == 'rascunho-automatico' || $pag->status == 'pendente' || $pag->status == 'lixeira')
													{
														?>
														<span data-status="publicado" id="button-publicar" class="button radius no-margin trigger-form-submit peixe-save" accesskey="s">Publicar</span>
														<?
													}
													else
													{
														?>
														<span class="button radius no-margin trigger-form-submit peixe-save" accesskey="s" id="button-publicar">Atualizar</span>
														<?
													}
												?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
			
						<div style="<?= ($pag->hideFormField('categorias') && $tipo != 'pagina') || ($pag->hideFormField('atributos') && $tipo == 'pagina') ? 'display: none;' : '' ?>" class="wrapper-pagina-field-<?= $pag->tipo == 'pagina' ? 'atributos' : 'categorias' ?>">
							<?php
								//implementação de categorias, vai ter que ficar para depois. Fazer páginas como categorias está resolvendo por enquanto.
								if($tipo != 'pagina' && class_exists('categoria'))
								{
									require_once(DBO_PATH.'/core/dbo-categoria-admin.php');
									echo renderCategoriaPaginaFormWidget($pag, $tipo);
								}
							?>
						</div>

						<div class="panel font-13 radius wrapper-pagina-field-imagem_destaque" id="wrapper-imagem-destacada" style="<?= $pag->hideFormField('imagem_destaque') ? 'display: none;' : '' ?>">
							<div class="row">
								<div class="large-12 columns">
									<strong>Imagem destacada</strong>
									<hr class="small">
									<div id="wrapper-imagem-destacada"><?= $pag->getFormElement($operation, 'imagem_destaque', array(
											'max_width' => '100%',
										)); ?></div>
								</div>
							</div>
						</div>
			
						<? $hooks->do_action('dbo_'.$tipo.'_form_sidebar_append', $pag, $params); ?>
					</div>
			
					<? $hooks->do_action('dbo_'.$tipo.'_form_sidebar_after', $pag, $params); ?>
				
				</div>
			</div>
			<input type="hidden" name="status" id="input-status" value="<?= $pag->status ? $pag->status : 'rascunho' ?>"/>
			<?= CSRFInput(); ?>
		</form>
		<script>

			var form_pagina_dirty = false;

			function closeClosestWrapperPubOption(obj) {
				obj.closest('.wrapper-pub-option').slideUp('fast', function(){
					$(this).prev('p').find('.trigger-pub-option').show();
				})
			}
			
			function getNewSlug(slug) {
				peixeJSONSilent('dbo/core/dbo-pagina-ajax.php?action=get-new-slug', {
					slug: slug,
					DBO_CSRF_token: '<?= CSRFGetToken() ?>'
				}, null, true);
			}

			/*function updateVisualEditor() {
				mce_container = $(tinyMCE.activeEditor.getContainer());
				code_container = $('#texto-codigo');

				html_code = autop(code_container.val());
				tinyMCE.activeEditor.setContent(html_code);
			}*/

			/*function updateCodeEditor() {
				mce_container = $(tinyMCE.activeEditor.getContainer());
				code_container = $('#texto-codigo');
				//console.log(tinyMCE.activeEditor.getHeight());

				//seta o tamanho da textarea para o tamanho do editor
				code_container.height(mce_container.height());
				mce_container.hide();
				clean_code = tinyMCE.activeEditor.getContent();
				clean_code = clean_code.replace(/<p>(.+)<\/p>\r?\n?/gim, "\$1\n\n");
				clean_code = clean_code.replace(/<br ?\/?>\s?/gim,"\n");
				clean_code = clean_code.trim();
				clean_code = clean_code.replace(/>\n</gim,">__dbo-line-break-flag__<");
				clean_code = clean_code.replace(/>\n(\S)/gim,">\n\n$1");
				clean_code = clean_code.replace(/__dbo-line-break-flag__/gim,"\n");
			}*/

			$(document).ready(function(){

				//mostrando opções de publicação
				$(document).on('click', '.trigger-pub-option', function(e){
					e.preventDefault();
					clicado = $(this);
					clicado.hide();
					clicado.closest('p').next('.wrapper-pub-option').slideDown('fast');
				});

				//escondendo opções de publicação
				$(document).on('click', '.trigger-cancel-pub-option', function(e){
					e.preventDefault();
					closeClosestWrapperPubOption($(this));
				});

				//submitando o formulário.
				$(document).on('click', '.trigger-form-submit', function(){
					clicado = $(this);
					form = $('#form-pagina');
					form_pagina_dirty = false;

					//atualizando o status dependendo de qual botão clicar.
					if(clicado.data('status')){
						$('#input-status').val(clicado.data('status'));
					}
					peixeJSON(form.attr('action'), form.serialize(), '', true);
					return false;
				});

				//detectando quando a data de publicação é limpa pelo script
				$(document).on('clear', '#data-publicacao', function(){
					$('#wrapper-data-publicacao').text('Agora');
					$('#button-publicar').text('Publicar').data('status', 'publicado');
					closeClosestWrapperPubOption($(this));
				});

				//alterando a data de publicação
				$(document).on('change', '#data-publicacao', function(){
					$('#wrapper-data-publicacao').text($(this).val());
				});

				$(document).on('update', '#data-publicacao', function(e, data){

					//pegando o horário atual
					agora = new Date();
					agora = agora.dateTime();

					//pegando o horário escolhido
					data.date.split(' ').list('data_escolhida', 'horario_escolhido');
					data_escolhida.split('/').list('dia', 'mes', 'ano');
					data_escolhida = ano + '-' + mes + '-' + dia + ' ' + horario_escolhido;

					data_escolhida > agora ? (msg='Agendar',status='') : (msg='Publicar',status='publicado');

					$('#button-publicar').text(msg).data('status', status);

					closeClosestWrapperPubOption($(this));
				});

				//pegando uma slug para a página atual baseado no titulo do post
				$(document).on('change', '#pagina-titulo', function(){
					c = $(this);
					if($.trim(c.val()) != '' && c.data('generate_slug') == true){
						getNewSlug(c.val());
					}
				});

				//controlando os botoes do formulario da slug
				$(document).on('click', '.trigger-slug-edit', function(e){
					e.preventDefault();
					$('#wrapper-slug-view').toggle();
					$('#wrapper-slug-edit').toggle();
					input = $('#pagina-slug');
					if(input.is(':visible')){
						input.focus();
					}
				});

				//atribuindo a nova slug
				$(document).on('keypress', '#pagina-slug', function(e){
					if(e.which == 13){
						$('.trigger-slug-save').trigger('click');
					}
				});

				$(document).on('click', '.trigger-slug-save', function(){
					i = $('#pagina-slug');
					if($.trim(i.val()) != '' && i.val() != i.data('slug_atual')){
						getNewSlug(i.val());
					}
					else if(i.val() == i.data('slug_atual')){
						$('#slug-label').text(i.val());
						$('#wrapper-slug-view').toggle();
						$('#wrapper-slug-edit').toggle();
					}
				});

				//alternando do editor visual para codigo e vice-versa
				$(document).on('click', 'dd a.trigger-editor-codigo', function(e){
					e.preventDefault();
					c = $(this);
					dd = c.closest('dd');
					if(dd.hasClass('active')){
						return;
					}
					else {
						dd.closest('dl').find('dd.active').removeClass('active');
						dd.addClass('active');
						trocaEditor();
					}
				});

				$(document).on('click', 'dd a.trigger-editor-visual', function(e){
					e.preventDefault();
					c = $(this);
					dd = c.closest('dd');
					if(dd.hasClass('active')){
						return;
					}
					else {
						dd.closest('dl').find('dd.active').removeClass('active');
						dd.addClass('active');
						trocaEditor();
					}
				});

				$(document).delegate('.code-editor', 'keydown', function(e) {
					var keyCode = e.keyCode || e.which;

					if (keyCode == 9) {
						e.preventDefault();
						var start = $(this).get(0).selectionStart;
						var end = $(this).get(0).selectionEnd;

						// set textarea value to: text before caret + tab + text after caret
						$(this).val($(this).val().substring(0, start)
												+ "\t"
												+ $(this).val().substring(end));

						// put caret at right position again
						$(this).get(0).selectionStart =
						$(this).get(0).selectionEnd = start + 1;
					}
				});					

				setTimeout(function(){
					$('#texto_ifr').scrollLock();
					//$('#texto-codigo').scrollLock();
					$('#texto').scrollLock();
				}, 1000);

				$(document).on('input', '#form-pagina :input', function(){
					form_pagina_dirty = true;
				});

				$(document).on('change', '#form-pagina input[type="radio"], #form-pagina input[type="checkbox"]', function(){
					form_pagina_dirty = true;
				});

				window.addEventListener("beforeunload", function (e) {
					if(form_pagina_dirty) {
						var confirmationMessage = 'Parece que você não salvou seu formulário. '
												+ 'Se você sair em salvar, suas alterações serão perdidas.';

						(e || window.event).returnValue = confirmationMessage; //Gecko + IE
						return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
					}
				});

			}) //doc.ready
		</script>
		<?
		return ob_get_clean();
	}

	function paginaCreateMediaPage($file_name, $params = array())
	{
		extract($params);

		$titulo = $titulo ? $titulo : preg_replace('/\\.[^.\\s]{3,4}$/', '', $file_name);

		$pag = new pagina();
		$pag->titulo = $titulo;
		$pag->data = dboNow();
		$pag->tipo = 'midia';
		$pag->autor = loggedUser();
		$pag->created_by = loggedUser();
		$pag->created_on = dboNow();
		$pag->slug = dboUniqueSlug($titulo, 'database', array(
			'table' => $pag->getTable(),
			'column' => 'slug',
		));
		$pag->imagem_destaque = $file_name;
		$pag->status = 'publicado';
		if($modulo)
		{
			$pag->modulo_anexado = $modulo;
		}
		if($modulo_id)
		{
			$pag->modulo_anexado_id = $modulo_id;
		}
		$pag->save();

		//esta flag vai dizer se a slug deve ser mudada na primeira alteração de título.
		if('update_slug')
		{
			$pag->setDetail('update_slug', true);
			$pag->update();
		}
	}

	function autoAdminPagina($params = array())
	{
		global $dbo;
		global $hooks;
		global $tipo;
		global $_system;
		global $_pes;

		extract($params);

		//verificando se o tipo de pagina existe setada no sistema
		$tipo = is_array($_system['pagina_tipo'][$tipo]) ? $tipo : 'pagina';

		//extrai as informações do tipo customizado, senão extrai da página mesmo.
		extract($_system['pagina_tipo'][$tipo]);

		//juntando os parametros com o tipo de objeto, para os formulários necessários.
		$params = array_merge($params, $_system['pagina_tipo'][$tipo]);

		//instanciando a página, se existe
		$pag = new pagina($_GET['dbo_update']);

		//instanciando um objeto para categorias
		$cat = new categoria();

		//variaveis padrão
		$list_view = ($_GET['list_view'] ? $_GET['list_view'] : ($default_list_view ? $default_list_view : 'list'));
		$paginacao = $paginacao === null ? 20 : $paginacao;
		$order_by = $order_by !== null ? $order_by : 'titulo';
		$order = $order !== null ? $order : 'ASC';
		$list_columns = $list_columns !== null ? $list_columns : array('titulo', 'categorias', 'nome_autor', 'data');

		ob_start();
		?>
		<div class="row" style="position: relative;">
			<div class="large-9 columns">
				<?php
					$stack = array();
					if(!$pag->hideBreadcrumbsRoot())
					{
						$stack[] = array(
							'tipo' => 'url',
							'url' => 'cadastros.php',
							'label' => DBO_TERM_CADASTROS,
						);
					}
					$stack[] = array(
						'tipo' => 'url',
						'url' => $dbo->keepUrl('!dbo_new&!dbo_update'),
						'label' => ucfirst($titulo_big_button ? $titulo_big_button : $titulo_plural),
					);
					if($_GET['dbo_new'] || $_GET['dbo_update']) 
					{
						$stack[] = array(
							'tipo' => 'url',
							'url' => $pag->keepUrl(),
							'label' => $pag->id ? $pag->getBreadcrumbIdentifier() : 'Nov'.$genero.' '.$titulo,
							'params' => array(
								'id' => 'breadcrumb-item-atual',
							),
						);
					}
					echo dboBreadcrumbs(array(
						'stack' => $stack,
					));
				?>
			</div>
			<div class="large-3 columns text-right">
				<?php
					$insert_button = '<a href="'.$dbo->keepUrl('dbo_new=1').'" title="Adicionar nov'.$genero.'" class="button small radius no-margin top-less-15 trigger-nova-pagina"><i class="fa fa-plus"></i>[placeholder]</a>';
				?>
				<?= ((hasPermission('insert', 'pagina-'.$tipo) && !$_GET['dbo_new'] && !$_GET['dbo_update'])?(str_replace('[placeholder]', ' Nov'.$genero.' '.$titulo.'', $insert_button)):('')) ?>
				<?= (($_GET['dbo_new'] || $_GET['dbo_update'])?('<a href="'.$dbo->keepUrl('!dbo_new&!dbo_update').'" class="button small radius no-margin top-less-15 secondary"><i class="fa fa-arrow-left"></i> Voltar</a> '.str_replace(array('[placeholder]', ' seco '), array('', ' secondary '), $insert_button)):('')) ?>
			</div>
		</div>
		<hr class="small">
		<div class="row almost full" style="position: relative;">
			<div class="settings-toolbar">
				<?php
					if($_GET['dbo_update'])
					{
						?><span class="color light pointer tip-top toggle-settings-box" data-settings-box="settings-form-pagina" title="Configurações do formulário"><i class="fa fa-eye"></i> Campos</span><?php
					}
					else
					{
						?><span class="color light pointer tip-top toggle-settings-box" data-settings-box="settings-list-pagina" title="Configurações de exibição e leitura d<?= $_system['pagina_tipo'][$tipo]['genero'] ?>s <?= $_system['pagina_tipo'][$tipo]['titulo_plural'] ?>"><i class="fa fa-cog"></i> Configurações</span><?php
					}
				?>
			</div>
		</div>
		<div id="pagina-canvas" style="padding-bottom: 200px;">
			<?
				//listagem
				if(!$_GET['dbo_new'] && !$_GET['dbo_update'])
				{
					//removendo todos os rascunho automaticos do usuário ativo
					if($_GET['pagina_status'] == 'lixeira')
					{
						pagina::excluirNaoSalvos(array(
							'tipo' => $_GET['dbo_pagina_tipo'],
							'created_by' => loggedUser(),
						));
					}
					?>
					<style>
						.list-pagina td { vertical-align: top; }
						tfoot th { border-bottom: 1px solid #ddd; }
						thead th { border-top: 1px solid #ddd; }
						i.fa.fa-star.destaque { color: #F1CE00 !important; }
					</style>
					<div class="settings-box closed" id="settings-list-pagina">
						<div class="row">
							<div class="large-12 columns">
								<h3>Configurações</h3>
								<p class="font-14">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Inventore voluptatem illo deleniti consequatur aperiam numquam a sit odit nesciunt iste et temporibus tempora accusantium laborum unde animi voluptatum amet adipisci quasi repudiandae ipsam laudantium ab officia qui expedita facere ratione libero dolore reprehenderit enim. Id officiis praesentium enim totam doloribus repellat molestiae placeat eius nobis magni temporibus dignissimos commodi eaque odio inventore deserunt sequi a. Labore qui architecto omnis eos rerum modi unde eum fugit repellat nemo consectetur veritatis. Placeat quam culpa corporis cumque facere illo eos doloremque minus nihil beatae explicabo odio quaerat laboriosam impedit aut inventore reprehenderit ad?</p>
							</div>
						</div>
					</div>
					<div class="row almost full list-pagina" id="list-<?= $tipo ?>" class="list-pagina" style="position: relative;">
						<div class="large-12 columns">
							<?

								//partes do SQL
								$sql_part_status = ($_GET['pagina_status'] != 'lixeira')?(($_GET['pagina_status'])?(" AND pag.status = '".dboescape($_GET['pagina_status'])."' "):(" AND pag.status != 'lixeira' ")):(" AND pag.status = 'lixeira' ");

								//data
								$sql_part_data = (!empty($_GET['m']) ? " AND DATE_FORMAT(data, '%Y-%m') = '".dboescape($_GET['m'])."' " : '');


								//verificando se a pagina tem modulo extendido
								if($extension_module)
								{
									$ext_columns = array();
									$ext_columns_joins = array();
									$ext = new $extension_module();
									foreach($list_columns as $key => $column)
									{
										if(strpos($column, 'ext_') === 0)
										{
											$column = preg_replace('/^ext_/is', '', $column);
											//se for um join, é preciso criar uma lista separada com informações o LEFT JOIN de terceiro nivel no SQL
											if($ext->isJoin($column))
											{
												$ext_columns_joins[$column] = array(
													'alias' => $ext->getTable().'_join_'.$key,
													'module' => $ext->getJoinModule($column, false),
													'key' => $ext->getJoinKey($column),
													'label' => $ext->getJoinLabel($column),
												);
												$ext_columns[$key] = $ext_columns_joins[$column]['alias'].'.'.$ext_columns_joins[$column]['label'];
											}
											else
											{
												$ext_columns[$key] = $ext->getTable().'.'.$column;
											}
										}
									}
									//criando colunas mais simples de valor direto, sem joins
									foreach((array)$ext_columns as $key => $column)
									{
										$sql_part_extension_module .= ", ".$column." AS ".$list_columns[$key];
									}
									//tratando os joins agora...
									foreach((array)$ext_columns_joins as $key => $info)
									{
										$join_mod = $info['module'];
										$join_mod = new $join_mod();
										$sql_part_extension_module_joins .= "
											LEFT JOIN ".$join_mod->getTable()." ".$info['alias']." ON
												".$info['alias'].".".$info['key']." = ".$extension_module.".".$key."
										";
									}
								}

								//search
								if(!empty($_GET['s']))
								{
									$parts = array();
									$terms = explode(" ", $_GET['s']);
									foreach($terms as $term)
									{
										$search_conditions = array();
										//sempre busca no titulo e no texto
										$search_conditions[] = " pag.titulo LIKE '%".dboescape($term)."%' ";
										$search_conditions[] = " pag.texto LIKE '%".dboescape($term)."%' ";
										//se houver campos extendidos na listagem, tem que buscar neles também.
										foreach((array)$ext_columns as $column)
										{
											//campo extendido, sempre buscável
											$search_conditions[] = " ".$column." LIKE '%".dboescape($term)."%' ";
										}
										$parts[] = "( ".implode(" OR \n", $search_conditions)." \n)";
									}
									$sql_part_search = "\n AND ".implode("\n AND ", $parts);
								}

								//sql categorias
								if($tipo != 'pagina')
								{
									$sql_part_categoria_select = "
										, GROUP_CONCAT(
												DISTINCT c.nome 
												ORDER BY c.nome
												SEPARATOR ', '
										) AS categorias 
									";
									$sql_part_categoria_join = " 
										LEFT JOIN pagina_categoria pc ON 
											pc.pagina = pag.id
										LEFT JOIN categoria c ON
											c.id = pc.categoria
									";
									if($_GET['cat'] == 'sc')
									{
										$sql_part_categoria_where = " AND pag.id NOT IN (SELECT pagina FROM pagina_categoria WHERE pagina_categoria.pagina = pag.id) ";
									}
									elseif($_GET['cat'] > 0)
									{
										$sql_part_categoria_where = " AND c.id = ".dboescape($_GET['cat'])." ";
									}
								}

								//select boxes

								//pegando todas as categorias para o select box
								if($tipo != 'pagina')
								{
									$cat = new categoria("WHERE pagina_tipo = '".$tipo."' ORDER BY order_by");
									if($cat->size())
									{
										do {
											$categorias[] = array(
												'id' => $cat->id,
												'nome' => $cat->nome,
											);
										}while($cat->fetch());
									}
								}

								//pegando o range de datas
								$meses = array();
								$sql = "
									SELECT 
										DATE_FORMAT(pag.data, '%Y-%m') AS mes 
										FROM ".$pag->getTable()." pag
										".($ext ? " JOIN ".$ext->getTable()." ON ".$ext->getTable().".".$ext->getPK()." = pag.id " : "")."
										".$sql_part_extension_module_joins."
									WHERE 
										pag.tipo = '".$tipo."'
										".$sql_part_status."
										".$sql_part_search."
										AND pag.status != 'rascunho-automatico'
									GROUP BY mes
									ORDER BY mes DESC;
								";
								$res = dboQuery($sql);
								if(dboAffectedRows())
								{
									while($lin = dboFetchObject($res))
									{
										$meses[] = $lin->mes;
									}
								}

								//listando todas as páginas
								$sql = "
									SELECT 
										SQL_CALC_FOUND_ROWS
										pag.*,
										aut.nome AS nome_autor
										".$sql_part_categoria_select."
										".$sql_part_extension_module."
									FROM ".$pag->getTable()." pag
									LEFT JOIN ".$_pes->getTable()." aut ON
										pag.autor = aut.id
										".$sql_part_categoria_join."
										".($ext ? " JOIN ".$ext->getTable()." ON ".$ext->getTable().".".$ext->getPK()." = pag.id " : "")."
										".$sql_part_extension_module_joins."
									WHERE
										pag.tipo = '".$tipo."' 
										".$sql_part_categoria_where."
										".$sql_part_status."
										".$sql_part_data."
										".$sql_part_search."
										AND pag.status != 'rascunho-automatico'
										".((!hasPermission('all', 'pagina-'.$tipo))?(" AND autor = '".loggedUser()."'"):(''))."
									GROUP BY 
										pag.id
									ORDER BY
										".($_GET['order_by'] ? dboescape($_GET['order_by']) : $order_by)." ".($_GET['order'] ? dboescape($_GET['order']) : $order)."
								";
								$pag = new pagina();
								$pag->forcePagination($paginacao);
								$pag->query($sql);
							?>
							<div class="row">
								<div class="small-12 large-din-left columns">
									<?= $pag->renderStatusSelector(array(
										'genero' => $genero,
										'tipo' => $tipo,
										'active' => (($_GET['pagina_status'])?($_GET['pagina_status']):(false)),
									)) ?>
								</div>
								<div class="small-12 large-din-right columns">
									<div class="row collapse">
										<div class="small-9 large-din-left columns" id="list-search">
											<input type="search" name="s" id="" class="font-12" value="<?= htmlSpecialChars($_GET['s']) ?>" placeholder="Procurar <?= $titulo_plural ?>"/>
										</div>
										<div class="small-3 large-din-left columns end">
											<span class="button secondary radius postfix font-12 trigger-search"><i class="fa fa-search"></i></span>
										</div>
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="small-12 columns large-din-left">
									<div class="row collapse">
										<div class="small-9 large-din-left columns">
											<select class="font-12 acoes-em-massa">
												<option value="">Ações em massa</option>
												<?php
													if($_GET['pagina_status'] == 'lixeira')
													{
														?>
														<option value="restaurar-multi">Restaurar</option>
														<option value="excluir-multi">Excluir definitivamente</option>
														<?php
													}
													else
													{
														?>
														<option value="lixeira-multi">Lixeira</option>
														<?php
													}
												?>
											</select>
										</div>
										<div class="small-3 large-din-left columns">
											<span class="button secondary postfix font-12 radius trigger-aplicar-acoes-em-massa">Aplicar</span>
										</div>
									</div>
								</div>
								<div class="small-12 columns large-din-left end">
									<div class="row collapse">
										<div class="small-9 large-din-left columns">
											<select name="m" class="font-12" id="list-data-selector">
												<option value="">Mostrar todas as datas</option>
												<?php
													if(sizeof($meses))
													{
														foreach($meses as $mes)
														{
															?>
															<option value="<?= $mes ?>" <?= $_GET['m'] == $mes ? 'selected' : '' ?>><?= dboDate('F Y', strtotime($mes)) ?></option>
															<?php
														}
													}
												?>
											</select>
										</div>
										<div class="small-3 large-din-left columns">
											<span class="button secondary postfix font-12 radius trigger-filtrar-por-data">Filtrar</span>
										</div>
									</div>
								</div>
								<?php
									if($categorias)
									{
										?>
										<div class="small-12 columns large-din-left end">
											<div class="row collapse">
												<div class="small-9 large-din-left columns">
													<select name="m" class="font-12" id="list-categoria-selector">
														<option value="">Mostrar todas as categorias</option>
														<option value="sc" <?= $_GET['cat'] == 'sc' ? 'selected' : '' ?>>- Sem categoria -</option>
														<?php
															foreach($categorias as $cat)
															{
																?>
																<option value="<?= $cat[id] ?>" <?= $_GET['cat'] == $cat[id] ? 'selected' : '' ?>><?= $cat[nome] ?></option>
																<?php
															}
														?>
													</select>
												</div>
												<div class="small-3 large-din-left columns">
													<span class="button secondary postfix font-12 radius trigger-filtrar-por-categoria">Filtrar</span>
												</div>
											</div>
										</div>
										<?php
									}
								?>
								<div class="small-12 large-din-right columns">
									<span class="font-14 form-height-fix">
										<span id="list-view-selector">
											<i data-url="<?= $dbo->keepUrl('list_view=list') ?>" class="fa fa-list pointer peixe-reload <?= $list_view == 'list' ? '' : 'color light' ?>" title="Visão de lista" peixe-reload="#wrapper-list-table,#status-selector,#list-view-selector"></i> &nbsp;
											<i data-url="<?= $dbo->keepUrl('list_view=details') ?>" class="fa fa-th-list pointer peixe-reload <?= $list_view == 'details' ? '' : 'color light' ?>" title="Visão detalhada" peixe-reload="#wrapper-list-table,#status-selector,#list-view-selector"></i> &nbsp;
											<i data-url="<?= $dbo->keepUrl('list_view=gallery') ?>" class="fa fa-th-large pointer peixe-reload <?= $list_view == 'gallery' ? '' : 'color light' ?>" title="Visão de galeria" peixe-reload="#wrapper-list-table,#status-selector,#list-view-selector"></i> &nbsp;
										</span>
										<span class="color medium font-12 list-numero-itens"><em><?= intval($pag->total())." ".(($pag->total() > 1 || !$pag->total())?('itens'):('item')) ?></em></span>
										<span id="list-pagination"><?= $pag->splitter(null, array(
											'display' => 'inline-block',
											'margin' => 0,
											'font_size' => '14px',
											'layout' => 'compact',
											'peixe_reload' => '#list-pagination,#list-pagination-bottom,#list-view-selector,#list-table-rows',
										)); ?></span>
									</span>
								</div>
							</div>
							
							<div id="wrapper-list-table">
								<table class="responsive <?= $list_view ?>" id="list-table">
									<thead>
										<tr>
											<th style="width: 30px;"><input type="checkbox" name="" id="" value="" class="no-margin top-2 trigger-check-all"/></th>
											<?php
												foreach($list_columns as $column)
												{
													if($column == 'categorias' && $tipo == 'pagina')
													{
														continue;
													}
													else
													{
														?>
														<th style="<?= $pag->getListColumnStyles($column) ?>"><?= $pag->getLinkOrderBy($column, $pag->getListLabel($column, $_system['pagina_tipo'][$tipo])) ?></th>
														<?php
													}
												}
											?>
										</tr>
									</thead>
									<tbody id="list-table-rows">
										<?
											if($pag->size())
											{
												do {
													?>
													<tr id="list-item-<?= $pag->id ?>">
														<?php
															if($list_view != 'gallery')
															{
																?>
																<td><input type="checkbox" name="selected[]" id="" value="<?= $pag->id ?>" class="no-margin top-2 stop-propagation list-checkable"/></td>
																<?php
															}
															$first = true;
															foreach($list_columns as $column)
															{
																if($first)
																{
																	?>
																	<td style="<?= $list_view == 'gallery' ? 'background-image: url('.$pag->imagemUrl(array('size' => 'small', 'show_placeholder' => true)).')' : '' ?>">
																		<?php
																			if($list_view == 'gallery')
																			{
																				?>
																				<input type="checkbox" name="selected[]" id="selected-<?= $pag->id ?>" value="<?= $pag->id ?>" class="no-margin top-2 stop-propagation list-checkable"/><label for="selected-<?= $pag->id ?>"></label>
																				<?php
																			}
																		?>
																		<span class="info">
																			<?= $pag->destaque() ? '<a href="'.secureUrl('dbo/core/dbo-pagina-ajax.php?action=remover-destaque&pagina_id='.$pag->id.'&'.CSRFVar()).'" title="Remover destaque" class="peixe-json peixe-reload" data-keep-url="'.keepUrl().'" peixe-reload="#list-item-'.$pag->id.'"><i class="fa fa-star destaque"></i></a>' : '' ?> 

																			<?= $pag->inativo() ? '<a href="'.secureUrl('dbo/core/dbo-pagina-ajax.php?action=ativar&pagina_id='.$pag->id.'&'.CSRFVar()).'" title="Ativar" class="peixe-json peixe-reload" data-keep-url="'.keepUrl().'" peixe-reload="#list-item-'.$pag->id.'"><i class="fa fa-lock color alert"></i></a>' : '' ?>

																			<strong><a href="<?= $dbo->keepUrl('dbo_update='.$pag->id); ?>" style="padding-bottom: 4px; display: inline-block;"><?= $pag->getListIdentifier($column) ?></a></strong><?= $pag->status != 'publicado' ? '<span class="color medium"> &#8212; '.ucfirst($pag->status).'</span>' : '' ?><br />
																			<?php
																				if($list_view == 'details')
																				{
																					echo '<span class="font-12">'.$pag->resumo().'</span>';
																				}
																			?>
																			<div style="height: 13px;">
																				<span class="hover-info font-12">
																				<?php
																					if($_GET['pagina_status'] == 'lixeira')
																					{
																						?>
																						<a href="<?= secureUrl('dbo/core/dbo-pagina-ajax.php?action=restaurar&pagina_id='.$pag->id.'&'.CSRFVar()) ?>" class="peixe-json">Retaurar</a>
																						<span class="color light">&nbsp;|&nbsp;</span>
																						<a href="<?= secureUrl('dbo/core/dbo-pagina-ajax.php?action=excluir&pagina_id='.$pag->id.'&'.CSRFVar()) ?>" class="color alert peixe-json" data-confirm='Tem certeza que deseja excluir <?= $genero ?> <?= $titulo ?> "<?= $pag->titulo ?>" definitivamente?\n\nEsta ação é irreversível.'>Excluir definitivamente</a>
																						<?php
																					}
																					else
																					{
																						?>
																						<a href="<?= $dbo->keepUrl('dbo_update='.$pag->id); ?>">Editar</a>
																						<span class="color light">&nbsp;|&nbsp;</span>
																						<a href="<?= secureUrl('dbo/core/dbo-pagina-ajax.php?action=lixeira&pagina_id='.$pag->id.'&'.CSRFVar()) ?>" class="color alert peixe-json" data-confirm='Tem certeza que deseja enviar <?= $genero ?> <?= $titulo ?> "<?= $pag->titulo ?>" para a lixeira?'>Lixeira</a> 
																						<span class="color light">&nbsp;|&nbsp;</span>
																						<a href="<?= $pag->permalink(); ?>" target="_blank">Visualizar</a> 
																						
																						<?php
																							$keep_url = keepUrl();
																							if(!$pag->destaque())
																							{
																								?> <span class="color light">&nbsp;|&nbsp;</span> <a href="<?= secureUrl('dbo/core/dbo-pagina-ajax.php?action=destacar&pagina_id='.$pag->id.'&'.CSRFVar()) ?>" title="Destacar" class="peixe-json peixe-reload" data-keep-url="<?= $keep_url ?>" peixe-reload="#list-item-<?= $pag->id ?>"><i class="fa fa-star"></i></a><?php
																							}
																						?>
																						
																						<?php
																							if(!$pag->inativo())
																							{
																								?> <span class="color light">&nbsp;|&nbsp;</span> <a href="<?= secureUrl('dbo/core/dbo-pagina-ajax.php?action=desativar&pagina_id='.$pag->id.'&'.CSRFVar()) ?>" title="Desativar" class="peixe-json peixe-reload" data-keep-url="<?= $keep_url ?>" peixe-reload="#list-item-<?= $pag->id ?>"><i class="fa fa-unlock-alt"></i></a><?php
																							}
																						?>
																						<?php
																					}
																				?>
																				</span>
																			</div>
																		</span>
																	</td>
																	<?php
																}
																else
																{
																	//categorias ---------------------------------------------------------
																	if($column == 'categorias')
																	{
																		if($tipo == 'pagina') continue;
																		?>
																		<td class="font-12"><?= $pag->categorias ? $pag->categorias : '<span class="color medium">&#8212; Sem categoria &#8212;</span>' ?></td> 
																		<?php
																	}
																	elseif($column == 'data')
																	{
																		?>
																		<td class="font-12">
																			<?= dboDate('d/M/Y', strtotime($pag->data)) ?><br />
																			<span class="color medium"><?= $pag->getValue('status', $pag->status) ?></span>
																		</td>
																		<?php
																	}
																	else
																	{
																		?>
																		<td class="font-12"><?= $pag->getListIdentifier($column) ?></td>
																		<?php
																	}
																}
																$first = false;
															}
														?>
													</tr>
													<?
												}while($pag->fetch());
											}
											else
											{
												?>
												<tr>
													<td colspan="10" class="text-center"><h2 class="no-margin color medium" style="padding: 30px;">Nada aqui :(</h2></td>
												</tr>
												<?
											}
										?>
									</tbody>
									<tfoot>
										<tr>
											<th style="width: 30px;"><input type="checkbox" name="" id="" value="" class="no-margin top-2 trigger-check-all"/></th>
											<?php
												foreach($list_columns as $column)
												{
													if($column == 'categorias' && $tipo == 'pagina')
													{
														continue;
													}
													else
													{
														?>
														<th style="<?= $pag->getListColumnStyles($column) ?>"><?= $pag->getLinkOrderBy($column, $pag->getListLabel($column, $_system['pagina_tipo'][$tipo])) ?></th>
														<?php
													}
												}
											?>
										</tr>
									</tfoot>
								</table>
							</div>

							<div class="row">
								<div class="small-12 columns large-din-left">
									<div class="row collapse">
										<div class="small-9 large-din-left columns">
											<select class="font-12 acoes-em-massa">
												<option value="">Ações em massa</option>
												<?php
													if($_GET['pagina_status'] == 'lixeira')
													{
														?>
														<option value="restaurar-multi">Restaurar</option>
														<option value="excluir-multi">Excluir definitivamente</option>
														<?php
													}
													else
													{
														?>
														<option value="lixeira-multi">Lixeira</option>
														<?php
													}
												?>
											</select>
										</div>
										<div class="small-3 large-din-left columns">
											<span class="button secondary postfix font-12 radius trigger-aplicar-acoes-em-massa">Aplicar</span>
										</div>
									</div>
								</div>
								<div class="small-12 large-din-right columns">
									<span class="color medium font-12 list-numero-itens"><em><?= intval($pag->total())." ".(($pag->total() > 1 || !$pag->total())?('itens'):('item')) ?></em></span>
									<span id="list-pagination-bottom"><?= $pag->splitter(null, array(
										'display' => 'inline-block',
										'margin' => 0,
										'font_size' => '14px',
										'form' => false,
										'layout' => 'compact',
										'peixe_reload' => '#list-pagination,#list-pagination-bottom,#list-view-selector,#list-table-rows',
									)); ?></span>
								</div>
							</div>
						</div>
					</div>
					<script>

						function triggerSearch() {
							s = $('#list-search input').val();
							target = keepUrl('!pag&s='+s);
							peixeUpdateCurrentUrl(target);
							peixeGet(peixe_current_url, function(d){
								d = $.parseHTML(d);
								['#list-table-rows','#list-pagination','#list-pagination-bottom','.list-numero-itens'].forEach(function(v){
									peixeReload(v, d);
								})
							})
						}

						$(document).ready(function(){
							$(document).on('click', '#list-status-selector a', function(e){
								e.preventDefault();
								c = $(this);
								c.closest('dl').find('dd').removeClass('active');
								c.closest('dd').addClass('active');
							});

							//selecionando tudo
							$(document).on('click', '.trigger-check-all', function(){
								c = $(this);
								if(c.is(':checked')){
									$('.trigger-check-all').prop('checked', true);
									$('.list-checkable').prop('checked', true);
								}
								else {
									$('.trigger-check-all').prop('checked', false);
									$('.list-checkable').prop('checked', false);
								}
							});
							
							$(document).on('click', '.list-checkable', function(){
								c = $(this);
								if(!c.is(':checked')){
									$('.trigger-check-all').prop('checked', false);
								}
							});

							//aplicando acoes em massa
							$(document).on('change', '.acoes-em-massa', function(){
								c = $(this);
								$('.acoes-em-massa').val(c.val());
							});

							$(document).on('click', '.trigger-aplicar-acoes-em-massa', function(){
								//primeiro checanco se tem alguma coisa selecionada
								checados = $('.list-checkable:checked');
								if(checados.length){
									acao = $('.acoes-em-massa').val();
									if(!acao){
										alert('Você precisa selecionar uma ação da lista.');
									}
									else {
										//tratando ação por ação
										if(acao == 'lixeira-multi'){
											var ans = confirm("Tem certeza que deseja enviar os itens selecionados para a lixeira?");
										}
										else if(acao == 'excluir-multi'){
											var ans = confirm("Tem certeza que deseja excluir os itens selecionados definitivamente?\n\nEsta ação é irreversível.");
										}
										else if(acao == 'restaurar-multi'){
											var ans = true;
										}
										if(ans){
											peixeJSON('dbo/core/dbo-pagina-ajax.php?action='+acao, {
												pagina_ids: checados.map(function(){ return $(this).val() }).get(),
												DBO_CSRF_token: '<?= CSRFGetToken() ?>'
											}, null, true);
										}
									}
								}
								else {
									alert('Você precisa selecionar alguns itens para realizar uma ação em massa.');
								}
							});

							//filtrando por categoria
							$(document).on('click', '.trigger-filtrar-por-categoria', function(){
								peixeUpdateCurrentUrl(keepUrl('!pag&cat='+$('#list-categoria-selector').val()));
								peixeGet(peixe_current_url, function(d){
									d = $.parseHTML(d);
									['#list-table-rows','#list-pagination','#list-pagination-bottom','.list-numero-itens','#list-view-selector','#list-status-selector'].forEach(function(v){
										peixeReload(v, d);
									})
								})
							});

							//filtrando por data
							$(document).on('click', '.trigger-filtrar-por-data', function(){
								peixeUpdateCurrentUrl(keepUrl('!pag&m='+$('#list-data-selector').val()));
								peixeGet(peixe_current_url, function(d){
									d = $.parseHTML(d);
									['#list-table-rows','#list-pagination','#list-pagination-bottom','.list-numero-itens','#list-view-selector','#list-status-selector'].forEach(function(v){
										peixeReload(v, d);
									})
								})
							});

							//filtrando por busca
							$(document).on('keypress', '#list-search input', function(e){
								if(e.which == 13){
									triggerSearch();
								}
							});

							$(document).on('click', '.trigger-search', function(){
								triggerSearch();
							});

						}) //doc.ready
					</script>
					<?
				}
				//mostrando o formulário de inserção
				elseif($_GET['dbo_new'] || $_GET['dbo_update'])
				{
					?>
					<div class="settings-box closed" id="settings-form-pagina">
						<div class="row">
							<div class="large-12 columns">
								<h3>Campos exibidos</h3>
								<div class="font-14">
									<p>Selecione abaixo os campos que você gostaria de ver neste formulário.</p>
									<?php

										$campos = array(
											array(
												'name' => 'slug',
												'label' => 'Slug',
											),
											array(
												'name' => 'subtitulo',
												'label' => 'Subtítulo',
											),
											array(
												'name' => 'resumo',
												'label' => 'Resumo',
											),
											array(
												'name' => 'texto',
												'label' => 'Texto',
											),
											array(
												'name' => 'autor',
												'label' => 'Autor',
											),
											array(
												'name' => ($tipo == 'pagina' ? 'atributos' : 'categorias'),
												'label' => ($tipo == 'pagina' ? 'Atributos' : 'Categorias'),
											),
											array(
												'name' => 'imagem_destaque',
												'label' => 'Imagem destacada',
											),
										);

										if($tipo == 'pagina')
										{
											//removendo o campo "resumo" das paginas
											unset($campos[2]);
										}

										foreach($campos as $campo)
										{
											?>
											<input type="checkbox" id="preference-hidden-field-<?= $campo['name'] ?>" <?= $pag->hideFormField($campo['name']) ? '' : 'checked' ?>/><label for="preference-hidden-field-<?= $campo['name'] ?>" data-dbo-set-pref data-meta_key="form_pagina_<?= $tipo ?>_prefs" data-pref_key="hide_<?= $campo['name'] ?>" data-pref_value="<?= $pag->hideFormField($campo['name']) ? 'false' : 'true' ?>" data-toggle onClick="togglePaginaFormField('<?= $campo['name'] ?>')"><?= $campo['label'] ?></label>
											<?php
										}
									?>
								</div>
							</div>
						</div>
					</div>
					<?php
					echo paginaForm($pag, $params);
				}
			?>
		</div>
		<script>

			window.dboEditor = {

				//first_set: false,

				dboAutop: function(pee) {
					var preserve_linebreaks = false
					var preserve_br = false
					var blocklist = 'table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre' +
								'|form|map|area|blockquote|address|math|style|p|h[1-6]|hr|fieldset|legend|section' +
								'|article|aside|hgroup|header|footer|nav|figure|figcaption|details|menu|summary'

					if (pee.indexOf('<object') !== -1) {
						pee = pee.replace(/<object[\s\S]+?<\/object>/g, function (a) {
							return a.replace(/[\r\n]+/g, '')
						})
					}

					pee = pee.replace(/<[^<>]+>/g, function (a) {
						return a.replace(/[\r\n]+/g, ' ')
					})

					// Protect pre|script tags
					if (pee.indexOf('<pre') !== -1 || pee.indexOf('<script') !== -1) {
						preserve_linebreaks = true
						pee = pee.replace(/<(pre|script)[^>]*>[\s\S]+?<\/\1>/g, function (a) {
							return a.replace(/(\r\n|\n)/g, '<wp-line-break>')
						})
					}

					// keep <br> tags inside captions and convert line breaks
					if (pee.indexOf('[caption') !== -1) {
						preserve_br = true
						pee = pee.replace(/\[caption[\s\S]+?\[\/caption\]/g, function (a) {
							// keep existing <br>
							a = a.replace(/<br([^>]*)>/g, '<wp-temp-br$1>')
							// no line breaks inside HTML tags
							a = a.replace(/<[a-zA-Z0-9]+( [^<>]+)?>/g, function (b) {
								return b.replace(/[\r\n\t]+/, ' ')
							})
							// convert remaining line breaks to <br>
							return a.replace(/\s*\n\s*/g, '<wp-temp-br />')
						})
					}

					pee = pee + '\n\n'
					pee = pee.replace(/<br \/>\s*<br \/>/gi, '\n\n')
					pee = pee.replace(new RegExp('(<(?:' + blocklist + ')(?: [^>]*)?>)', 'gi'), '\n$1')
					pee = pee.replace(new RegExp('(</(?:' + blocklist + ')>)', 'gi'), '$1\n\n')
					pee = pee.replace(/<hr( [^>]*)?>/gi, '<hr$1>\n\n') // hr is self closing block element
					pee = pee.replace(/\s*<option/gi, '<option') // No <p> or <br> around <option>
					pee = pee.replace(/<\/option>\s*/gi, '</option>')
					pee = pee.replace(/\r\n|\r/g, '\n')
					pee = pee.replace(/\n\s*\n+/g, '\n\n')
					pee = pee.replace(/([\s\S]+?)\n\n/g, '<p>$1</p>\n')
					pee = pee.replace(/<p>\s*?<\/p>/gi, '')
					pee = pee.replace(new RegExp('<p>\\s*(</?(?:' + blocklist + ')(?: [^>]*)?>)\\s*</p>', 'gi'), '$1')
					pee = pee.replace(/<p>(<li.+?)<\/p>/gi, '$1')
					pee = pee.replace(/<p>\s*<blockquote([^>]*)>/gi, '<blockquote$1><p>')
					pee = pee.replace(/<\/blockquote>\s*<\/p>/gi, '</p></blockquote>')
					pee = pee.replace(new RegExp('<p>\\s*(</?(?:' + blocklist + ')(?: [^>]*)?>)', 'gi'), '$1')
					pee = pee.replace(new RegExp('(</?(?:' + blocklist + ')(?: [^>]*)?>)\\s*</p>', 'gi'), '$1')
					pee = pee.replace(/\s*\n/gi, '<br />\n')
					pee = pee.replace(new RegExp('(</?(?:' + blocklist + ')[^>]*>)\\s*<br />', 'gi'), '$1')
					pee = pee.replace(/<br \/>(\s*<\/?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)>)/gi, '$1')
					pee = pee.replace(/(?:<p>|<br ?\/?>)*\s*\[caption([^\[]+)\[\/caption\]\s*(?:<\/p>|<br ?\/?>)*/gi, '[caption$1[/caption]')

					pee = pee.replace(/(<(?:div|th|td|form|fieldset|dd)[^>]*>)(.*?)<\/p>/g, function (a, b, c) {
						if (c.match(/<p( [^>]*)?>/)) {
							return a
						}

						return b + '<p>' + c + '</p>'
					})

					// put back the line breaks in pre|script
					if (preserve_linebreaks) {
						pee = pee.replace(/<wp-line-break>/g, '\n')
					}

					if (preserve_br) {
						pee = pee.replace(/<wp-temp-br([^>]*)>/g, '<br$1>')
					}

					return pee
				},

				dboUnautop: function(pee) {
					pee = pee.replace(/<p>(.+)<\/p>\r?\n?/gim, "\$1\n\n");
					pee = pee.replace(/<br ?\/?>\s?/gim,"\n");
					pee = pee.trim();
					pee = pee.replace(/>\n</gim,">__dbo-line-break-flag__<");
					pee = pee.replace(/>\n(\S)/gim,">\n\n$1");
					pee = pee.replace(/__dbo-line-break-flag__/gim,"\n");
					return pee;
				},
			}


			<? $hooks->do_action('dbo_'.$tipo.'_javascript_prepend', $pag, $params); ?>

			//função para salvar a página quando der CTRL + S no editor de texto.
			/*function smartSave() {
				console.log('smart save');
				form = $('#form-pagina');
				peixeJSON(form.attr('action'), form.serialize(), '', true);
				return false;
			}*/

			//mostrando ou ocultando campos do formulário
			function togglePaginaFormField(id) {
				w = $('.wrapper-pagina-field-'+id);
				if(w.is(':visible')){
					w.slideUp('fast');
				} else {
					w.slideDown('fast');
				}
			}

			function editorInit(){
				$(".editor").each(function(){
					$(this).val(window.dboEditor.dboAutop($(this).val()));
					$(this).tinymce({
						height: (($(this).attr('rows'))?($(this).attr('rows')*19):('300')),
						theme: 'dbo',
						resize: false,
						//object_resizing: false,
						autoresize: true,
						autoresize_max_height: 600,
						language: 'pt_BR',
						autofocus: false,
						entity_encoding: 'named',
						entities: '160,nbsp',
						content_css: '<?= file_exists('css/tinymce.css') ? 'css/tinymce.css' : '' ?>',
						save_onsavecallback: function(){ /*smartSave();*/ },
						save_enablewhendirty: false,
						extended_valid_elements: 'div[media-manager-element|class|id],img[media-manager-element|src|alt|class|id|style]',

						plugins: [
							"save advlist lists link image charmap preview hr anchor pagebreak",
							"searchreplace wordcount visualblocks visualchars code fullscreen",
							"media nonbreaking table contextmenu directionality",
							"emoticons template paste textcolor dbo_media_manager dbo_column_manager autoresize dbo_editor"
						],
						toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | dbo_column_manager | link media dbo_media_manager | fullscreen"
					})
				})
				$('#texto').animate({ opacity: 1 }, 50).attr('spellcheck', false);
			}

			function paginaInit() {
				if(typeof dboInit == 'function'){
					dboInit();
				}
				peixeInit();
			}

			$(document).ready(function(){

				paginaInit();
				if(typeof iniciar_editor !== 'undefined' && iniciar_editor){
					editorInit();
				}

			}) //doc.ready

			<? $hooks->do_action('dbo_'.$tipo.'_javascript_append', $pag, $params); ?>

		</script>
		<?
		return ob_get_clean();
	}

	function paginaCriarRascunhoAutomatico($params = array())
	{
		global $hooks;
		extract($params);

		$pag = pagina::smartLoad(array(
			'tipo' => $tipo,
		));

		$pag->setAutoFields();

		$pag->tipo = $tipo;
		$pag->titulo = '(sem título)';
		//$pag->data = !strlen(trim($_POST['data'])) ? $pag->now() : $pag->data;
		$pag->status = 'rascunho-automatico';
		$pag->autor = loggedUser();

		//hook que faz alterações no objeto logo antes do save/update
		$pag = $hooks->apply_filters('dbo_pagina_pre_save', $pag);
		$pag = $hooks->apply_filters('dbo_pagina_'.$tipo.'_pre_save', $pag);

		$pag->saveOrUpdate();
		if($pag->mais())
		{
			$pag->mais()->saveOrUpdate();
		}

		return $pag;
	}

?>