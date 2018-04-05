<?

/* ================================================================================================================== */
/* DBO CLASS FILE FOR MODULE 'menu' ============================================= AUTO-CREATED ON 12/06/2015 16:49:15 */
/* ================================================================================================================== */

/* IMPORTANT: This file is generated only in the first DBO sync, what means you should edit only via text editor. */

if(!class_exists('menu'))
{
	class menu extends dbo
	{
		/* smart constructor: will perform load() upon numeric argument and loadAll() upon string argument */
		function __construct($foo = '')
		{
			parent::__construct('menu');
			if($foo != '')
			{
				if(is_numeric($foo))
				{
					$this->id = $foo;
					$this->load();
				}
				elseif(is_string($foo))
				{
					$this->loadAll($foo);
				}
			}
		}

		//your methods here
		static function render($slug = false, $params = array())
		{
			extract($params);
			if($slug != false)
			{
				$men = new menu("WHERE slug = '".$slug."'");
				if($men->size())
				{
					ob_start();
					if(!$items_only) { echo '<ul class="menu-root">'; }
					$items = json_decode($men->estrutura, true);
					foreach($items as $item)
					{
						echo menu::renderItem($item, $params);
					}
					if(!$items_only) { echo '</ul>'; }
					return ob_get_clean();
				}
			}
		}

		static function renderItem($item, $params = array())
		{
			extract($params);
			ob_start();
			if($item['tipo'] == 'pagina' || $item['tipo'] == 'categoria')
			{
				?><li class="<?= trim((is_array($item['children']) ? ($foundation_6 !== false ? 'has-submenu' : 'has-dropdown') : '').' '.($item['slug'] == $active_slug ? 'active' : '').' '.$item['classes']) ?> slug-<?= $item['slug'] ?>"><a href="<?= SITE_URL.($item['slug'] != 'home' ? '/'.$item['slug'] : ($hide_home ? '' : '/'.$item['slug'])) ?>"><?= $item['prepend'] ?><span><?= $item['titulo'] ?></span><?= $item['append'] ?></a><?
				if(is_array($item['children']))
				{
					echo '<ul class="'.($foundation_6 !== false ? 'submenu menu vertical' : 'dropdown').'" data-submenu>';
					foreach($item['children'] as $item)
					{
						echo menu::renderItem($item, $params);
					}
					echo '</ul>';
				}
				?></li><?
			}
			elseif($item['tipo'] == 'link')
			{
				?><li class="<?= ((is_array($item['children']))?($foundation_6 !== false ? 'has-submenu' : 'has-dropdown'):(''))." ".$item['classes'] ?>"><a <?= strlen(trim($item['target'])) ? 'target="'.$item['target'].'"' : '' ?> href="<?= $item['url'] ?>"><?= $item['prepend'] ?><span><?= $item['titulo'] ?></span><?= $item['append'] ?></a><?
				if(is_array($item['children']))
				{
					echo '<ul class="'.($foundation_6 !== false ? 'submenu menu vertical' : 'dropdown').'" data-submenu>';
					foreach($item['children'] as $item)
					{
						echo menu::renderItem($item, $params);
					}
					echo '</ul>';
				}
				?></li><?
			}
			return ob_get_clean();
		}
		
		function getEstruturaAdmin($params = array())
		{
			extract($params);
			$est = json_decode($this->estrutura, true);
			ob_start();
			if(is_array($est))
			{
				foreach($est as $value)
				{
					echo menu::gerarDDItemTemplate($value);
				}
			}
			return ob_get_clean();
		}

		static function gerarDDItemTemplate($data)
		{
			extract($data);

			//criando a string de data-attributes para o item
			foreach($data as $key => $value)
			{
				if(!is_array($value))
				{
					$str_data .= 'data-'.$key.'="'.htmlSpecialChars($value).'" ';
				}				
			}

			ob_start();
			?>
			<li class="dd-item" <?= $str_data ?>>
				<div class="dd-handle"><?= $titulo ?></div>
				<div class="dd-tipo closed"><?= (($tipo == 'pagina')?('Página'):((($tipo == 'link')?('Link'):($tipo == 'categoria' ? 'Categoria' : '')))) ?> <i class="fa fa-fw fa-caret-down icon-open"></i><i class="fa fa-fw fa-caret-up icon-closed"></i></div>
				<div class="panel dd-detalhes hide">
					<div class="row" style="<?= ((!hasPermission('menu-avancado'))?('display: none;'):('')) ?>">
						<div class="large-6 columns">
							<label for="">HTML prepend</label>
							<input type="text" name="prepend" id="" value="<?= htmlSpecialChars($prepend) ?>"/>
						</div>
						<div class="large-6 columns">
							<label for="">HTML append</label>
							<input type="text" name="append" id="" value="<?= htmlSpecialChars($append) ?>"/>
						</div>
					</div>
					<div class="row">
						<div class="large-6 columns">
							<label>Título</label>
							<input type="text" name="titulo" value="<?= htmlSpecialChars($titulo) ?>"/>
						</div>
						<div class="large-6 columns">
							<label>Classes CSS</label>
							<input type="text" name="classes" value="<?= htmlSpecialChars($classes) ?>"/>
						</div>
					</div>
					<?
						if($tipo == 'link')
						{
							?>
							<div class="row collapse">
								<div class="small-12 large-9 columns">
									<label for="">URL do link</label>
									<input type="text" name="url" value="<?= htmlSpecialChars($url) ?>"/>
								</div>
								<div class="small-12 large-3 columns">
									<label for="">Abrir em</label>
									<select name="target">
										<option value="">Mesma aba</option>
										<option value="_blank" <?= $target == '_blank' ? 'selected' : '' ?>>Nova aba</option>
									</select>
								</div>
							</div>
							<?
						}
					?>
					<div class="row">
						<div class="large-12 columns text-right">
							<?
								if($tipo == 'pagina' || $tipo == 'categoria')
								{
									?>
									<a href="<?= SITE_URL ?>/<?= $slug ?>" target="_blank">Visualizar página</a>
									<?
								}
								elseif($tipo == 'link')
								{
									?>
									<a href="<?= $url ?>" target="_blank">Acessar Link</a>
									<?
								}
							?>
							| <a href="#" class="trigger-excluir-item-menu">Excluir item</a>
						</div>
					</div>
				</div>
				<?
					if(is_array($children))
					{
						echo '<ol class="dd-list">';
						foreach($children as $child)
						{
							echo menu::gerarDDItemTemplate($child);
						}
						echo '</ol>';
					}
				?>
			</li>			
			<?
			return ob_get_clean();
		}

		//acerta uma slug de arquivo nos menus do sistema caso seja alterada em alguma página.
		static function updateSlug($old_slug, $new_slug)
		{
			$men = new menu('ORDER BY id');
			if($men->size())
			{
				do {
					$est = json_decode($men->estrutura, true);
					if(sizeof($est))
					{
						menu::updateSlugArray($est, $old_slug, $new_slug);
					}
					$men->estrutura = json_encode($est);
					$men->update();
				}while($men->fetch());					
			}
		}

		static function updateSlugArray(&$array, $old_slug, $new_slug)
		{
			foreach($array as $key => $info)
			{
				$parts = explode("/", $info['slug']);
				$slug = array_pop($parts);
				if($slug == $old_slug)
				{
					$parts[] = $new_slug;
					$array[$key]['slug'] = implode("/", $parts);
				}
				if(is_array($info['children']))
				{
					menu::updateSlugArray($array[$key]['children'], $old_slug, $new_slug);
				}
			}			
		}

	} //class declaration
} //if ! class exists

function auto_admin_menu()
{
	global $_system;

	ob_start();
	
	echo dboImportJs('nestable');

	?>
	<style>
		.accordion-navigation input[type="checkbox"] { margin-bottom: 8px; }
	</style>
	<div class="row">
		<div class="large-12 columns">
			<div class="breadcrumb">
				<ul class="no-margin">
					<li><a href="cadastros.php"><?= DBO_TERM_CADASTROS ?></a></li>
					<li><a href="#">Menus</a></li>
				</ul>
			</div>
		</div>
	</div>
	<hr>
	
	<div class="row almost full">
		<div class="large-4 columns">
			<ul class="accordion" data-accordion>
				<?
					if(class_exists('pagina'))
					{
						echo pagina::renderMenuAdminStructure('pagina');
						foreach($_system['pagina_tipo'] as $pagina_tipo => $dados)
						{
							if($pagina_tipo != 'pagina')
							{
								echo pagina::renderMenuAdminStructure($pagina_tipo);
							}
						}
						?>
						<script>
							$(document).ready(function(){
					
								$(document).on('click', '.trigger-adicionar-paginas', function(){
									if($('#nav-menus-disponiveis dd.active').length){
										pags = $('input[name^="item-pagina"]:checked');
										if(pags.length){
											pags.each(function(){
												adicionarDDItem($(this).data());
											})
										}
										else {
											alert('Selecione um ou mais itens da lista para adicionar ao menu ativo');
										}
									}
									else {
										alert('Erro: Não há nenhum menu cadastrado');
									}
								});
					
								$(document).on('click', '.trigger-selecionar-todas-paginas', function(e){
									e.preventDefault();
									c = $(this);
									c.closest('.content').find('input[name^="item-pagina"]:not("checked")').each(function(){
										$(this).prop('checked', true);
									})
								});
					
							}) //doc.ready
						</script>
						<?php
					}
					//verifica a classe categorias e se tem mais de 1 tipo de página alem do padrão
					if(class_exists('categoria') && sizeof($_system['pagina_tipo']) > 1) 
					{
						require_once(DBO_PATH.'/core/dbo-categoria-admin.php');
						echo renderCategoriaMenuAdminStructure();
					}
				?>
				<li class="accordion-navigation">
					<a href="#acc-links">Links personalizados</a>
					<div id="acc-links" class="content">
						<div class="row">
							<div class="large-12 columns">
								<label>Url</label>
								<input type="text" name="url" value="" placeholder="http://"/>
							</div>
							<div class="large-12 columns">
								<label>Texto do link</label>
								<input type="text" name="titulo" value=""/>
							</div>
						</div>
						<hr class="small">
						<div class="row">
							<div class="large-12 columns text-right"><span class="button radius small no-margin trigger-adicionar-link secondary">Adicionar ao menu <i class="fa-arrow-right fa"></i></span></div>
						</div>
					</div>
				</li>
			</ul>
		</div>
		<div class="large-8 columns" id="right-panel">
			<div class="row">
				<div class="large-8 columns">
					<dl class="sub-nav no-margin top-4" id="nav-menus-disponiveis">
						<dt>Menus:</dt>
						<?
							$men = new menu("ORDER BY id");
							if($men->size()) {
								do {
									?>
									<dd class="<?= (($men->getIterator() == $men->size())?('active'):('')) ?>" data-menu_id="<?= $men->id ?>" data-menu_profundidade="<?= $men->profundidade ?>"><a href="<?= $men->slug ?>"><?= $men->nome ?></a></dd>
									<?
								}while($men->fetch());								
							}
						?>
					</dl>			
				</div>
				<div class="large-4 columns text-right">
					<?
						if(hasPermission('insert', 'menu'))
						{
							?>
							<span class="button radius small no-margin top-less-11" id="button-cadastrar-novo"><i class="fa fa-plus fa-fw"></i> Cadastrar novo</span>
							<?
						}
					?>
					<span class="button radius small no-margin top-less-11 secondary" id="button-voltar" style="display: none;"><i class="fa fa-fw fa-arrow-left"></i> Voltar</span>
				</div>
			</div>
			<hr class="small">
			<div id="form-menu-update">
				<?
					//mostrando o seletor de menus, se houver menu disponivel
					if($men->size())
					{
						?>
							<div class="dd">
								<ol class="dd-list" style="min-height: 200px;" id="menu-canvas">
									<?
										echo $men->getEstruturaAdmin();
									?>
								</ol>
							</div>
							<hr>
							<div class="row">
								<div class="large-12 columns text-right">
									<?
										if(hasPermission('delete', 'menu'))
										{
											?>
											<a href="#" id="button-excluir-menu">Excluir menu</a> &nbsp;&nbsp;&nbsp;&nbsp;
											<?
										}
										if(hasPermission('update', 'menu'))
										{
											?>
											<span class="button radius peixe-save" id="button-salvar-menu">Salvar menu</span>
											<?
										}
									?>
								</div>
							</div>
						<?
					}
					else
					{
						?>
						<h3 class="text-center" style="padding-top: 5em;" id="msg-nao-ha-menus">- Não há menus cadastrados- </h3>
						<?
					}
				?>
			</div>
			<form method="post" action="dbo/core/dbo-menu-ajax.php?action=novo-menu" class="no-margin peixe-json" id="form-novo-menu" style="display: none;" peixe-log>
				<div class="row">
					<div class="large-8 columns item">
						<label for="input-novo-menu-nome">Digite o nome do novo menu</label>
						<input type="text" name="nome" id="input-novo-menu-nome" value="" required/>
					</div>
				</div>
				<?
					if(hasPermission('menu-avancado'))
					{
						?>
						<div class="row">
							<div class="large-4 columns item">
								<label for="input-novo-menu-profunidade">Profundidade máxima do menu</label>
								<input type="number" name="profundidade" id="input-novo-menu-profunidade" value="5" required/>
							</div>
						</div>
						<?
					}
				?>
				<div class="row">
					<div class="large-12 columns">
						<input class="button radius" type="submit" value="Inserir menu"/>
					</div>
				</div>
			</form>
		</div>
	</div>
	<script>
		
		function updateDDItemTitulo(dd_item) {
			dd_item.find('.dd-handle').text(dd_item.find('input[name="titulo"]').val());
		}

		function adicionarDDItem(data) {
			peixeJSON('dbo/core/dbo-menu-ajax.php?action=gerar-dd-item', data, '', false);
			$('input[name^="item-"]:checked').each(function(){
				$(this).prop('checked', false);
			});
		}

		function ddInit() {
			//$('.dd').nestable('destroy');
			$('.dd').nestable({ /*maxDepth: $('#nav-menus-disponiveis dd.active').data('menu_profundidade')*/ });
		}

		function reloadRightPanel() {
			peixeGet(document.URL, function(d) {
				var html = $.parseHTML(d);
				/* item 1 */
				handler = '#right-panel';
				content = $(html).find(handler).html();
				if(typeof content != 'undefined'){
					$(handler).fadeHtml(content, function(){ ddInit() });
				}
			})
			return false;
		}

		$(document).ready(function(){

			ddInit();

			$(document).on('click', '.trigger-serialize', function(){
				console.log($('.dd').nestable('serialize'));
			});

			$(document).on('click', '.dd-tipo', function(){
				clicado = $(this);
				if(clicado.hasClass('open')){
					clicado.removeClass('open').addClass('closed').closest('.dd-item').find('.dd-detalhes').first().slideUp('fast');
				} else {
					clicado.removeClass('closed').addClass('open').closest('.dd-item').find('.dd-detalhes').first().slideDown('fast');
				}
			});

			//atualizando os data-attributes no change
			$(document).on('change', '.dd-item input, .dd-item select', function(){
				mudado = $(this);
				dd_item = mudado.closest('.dd-item');
				dd_item.data(mudado.attr('name'), mudado.val());
				updateDDItemTitulo(dd_item);
			});

			//excluindo os menus
			$(document).on('click', '.trigger-excluir-item-menu', function(e){
				e.preventDefault();
				var ans = confirm("Tem certeza que deseja excluir este item do menu?");
				if (ans==true) {
					clicado = $(this);
					clicado.closest('.dd-item').fadeOut('fast', function(){
						$(this).remove();
					})
				} 
			});

			//enviando links personalizados ao menu
			$(document).on('click', '.trigger-adicionar-link', function(){
				if($('#nav-menus-disponiveis dd.active')){
					clicado = $(this);
					url = clicado.closest('.content').find('input[name="url"]').val();
					titulo = clicado.closest('.content').find('input[name="titulo"]').val();
					if($.trim(titulo) != '' && $.trim(url) != ''){
						adicionarDDItem({
							tipo: 'link',
							titulo: titulo,
							url: url
						})
					}
					else {
						alert('Preencha um título e uma url para adicionar o item ao menu');
					}
				}
				else {
					alert('Erro: não há nenhum menu cadastrado');
				}
			});

			//mostrando o formulário para inserir novo menu
			$(document).on('click', '#button-cadastrar-novo', function(){
				clicado = $(this);
				clicado.fadeOut('fast', function(){
					$('#button-voltar').fadeIn('fast');
				})
				$('#form-menu-update').fadeOut('fast', function(){
					$('#form-novo-menu').fadeIn('fast', function(){
						$('#input-novo-menu-nome').focus();
					});
				})
			});

			$(document).on('click', '#button-voltar', function(){
				clicado = $(this);
				clicado.fadeOut('fast', function(){
					$('#button-cadastrar-novo').fadeIn('fast');
				})
				$('#form-novo-menu').fadeOut('fast', function(){
					$('#form-menu-update').fadeIn('fast');
				})
			});			

			//salvando o menu ativo
			$(document).on('click', '#button-salvar-menu', function(){
				peixeJSON('dbo/core/dbo-menu-ajax.php?action=salvar-menu&menu_id='+$('#nav-menus-disponiveis dd.active').data('menu_id'), { 
					menu_data: $('.dd').nestable('serialize') 
				}, '', true);
			});

			//selecionando um menu
			$(document).on('click', '#nav-menus-disponiveis dd:not(.active) a', function(e){
				e.preventDefault();
				$('#nav-menus-disponiveis dd.active').removeClass('active');
				clicado = $(this).closest('dd');
				clicado.addClass('active');
				peixeJSON('dbo/core/dbo-menu-ajax.php?action=load-menu', { menu_id: clicado.data('menu_id') }, '', false);
			});
			$(document).on('click', '#nav-menus-disponiveis dd.active a', function(e){ e.preventDefault(); });

			//excluindo um menu
			$(document).on('click', '#button-excluir-menu', function(e){
				e.preventDefault();
				var ans = confirm("Tem certeza que deseja excluir este menu?");
				if (ans==true) {
					peixeJSON('dbo/core/dbo-menu-ajax.php?action=delete-menu', { menu_id: $('#nav-menus-disponiveis dd.active').data('menu_id') }, '', true);
				} 
			});

		}) //doc.ready
	</script>
	<?
	return ob_get_clean();
}

?>