<?php

/* define a senha mestra para login no site */
//define(MASTER_PASSWORD, 'e771ebe40a650de43bd9ed531015268d106700b5bda5a3a98b70d9bef1577642d13f75da34d97bd6f8ca0121019d6cacf79582b2708749e3899ba5c9da1de16b');

/* define se os e-mails serão ou não enviados por SMTP */
//define(DBO_MAIL_IS_SMTP, true);

/* define a url do site */
//define(SITE_URL, preg_replace('#/admin/dbo$#is', '', DBO_URL));

/* define uma pagina principal diferente do cadastros.php */
//define(DBO_ADMIN_INDEX, 'painel.php');

/* define a palavra principal do cockpit */
//define(DBO_TERM_CADASTROS, 'Cadastros');

/* definições de um header customizado para o admin */
/* colocar as imagens admin-bg.jpg e admin-logo.png dentro da pasta admin/images */
//$_system['pretty_header'] = array(
	//'theme' => 'dark',
	//'hide_menu' => true,
	//'height' => 210, //valor inteiro, sem px
	//'logo_height' => 140,
	//'logo_offset' => 85,
	//'parallax' => true,
	//'styles' => 'background-size: cover;',
//);

/* definindo tipos especiais de páginas no sistema */
//$_system['pagina_tipo']['animal'] = array(
	//'tipo' => 'animal',
	//'titulo' => 'animal',
	//'titulo_plural' => 'animais',
	//'titulo_big_button' => 'plantel',
	//'icone' => 'file-text',
	//'genero' => 'o',
	//'extension_module' => 'pagina_animal',
	//'slug_prefix' => 'plantel', //incluido antes do slug no permalink
	//'slug_date' => true, //mostra o ano e mes na slug da página
	//'default_list_view' => 'gallery', //list | details | gallery
	//'paginacao' => 20,
	//'order_by' => 'titulo',
	//'cockpit_order_by' => -2000,
	//'hidden_fields' => array( //array contendo o campos que não devem ser exibidos por padrão no formulário.
		//'slug',
		//'subtitulo',
		//'resumo',
		//'texto',
		//'autor',
		//'atributos',
		//'categorias',
		//'imagem_destaque',
	//), 
//);

/* definindo configuracoes espeficidas para slugs de páginas do sistema */
//$_system['settings']['pagina']['slug']['home'] = array(
	//'hidden_fields' => array(
		//'subtitulo',
		//'texto',
	//),
//);

/* tamanhos cutomizados de imagens do sistema */
//$_system['media_manager']['image_sizes'] = array(
	//'gigante' => array(
		//'name' => 'Gigante',
		//'max_width' => '3000',
		//'max_height' => '3000',
		//'quality' => '90'
	//),
	//'wide' => array(
		//'name' => 'Widescreen',
		//'max_width' => '1920',
		//'max_height' => '1080',
		//'quality' => '80'
	//)
//);

/* blocos de conteúdo. Uma caixinha de HTML ou outra coisa. Suportar Markdown */
/* usa o dboUI para gerar os dados. Usar as definições de parâmetro do dboUI. */
/* podem ser usadas em slugs espcíficas ou no contexto global */
//$_system['content_block']['pagina']['slug']['home'] = array(
	//'foto_sucos' => array(
		//'field_type' => 'media',
		//'label' => '',
		//'dica' => '',
		//'valores' => '',
		//'classes' => '',
		//'grid' => 6,
	//),
//);
//$_system['content_block']['global'] = array(
	//'descricao_sucos' => array(
		//'field_type' => 'textarea',
		//'label' => '',
		//'dica' => '',
		//'valores' => '',
		//'classes' => '',
		//'grid' => 6,
	//),
//);

/* define se as páginas devem ser cacheadas ou não */
/* para utilizar o cache, coloque dentro do functions.php, logo após os includes: require_once('dbo/core/dbo-cache.php'); */
/* criar, por padrão, a pasta "...dbo/cache" */
/* no início de cada página no frontend, utilizar o snippet html "dbocache" */
//define(DBO_CACHE_PAGES, false);

/* definições de duração do cache para as páginas (unidades: s - segundos, m - minutos, h - horas, d - dias, w - semanas, false - desativa o cache */
//$_system['cache_settings']['expire']['global'] = '1h'; //definição global de cache que sera aplicada se a fornecida for null
//$_system['cache_settings']['expire']['slug']['home'] = '1h'; //definição especifica para esta slug
//$_system['cache_settings']['expire']['block']['sidebar'] = '30s'; //definição especifica para este bloco

/* blacklist de módulos. Os módulos listados aqui não será carregados no sistema. */
//$_system['module_blacklist'] = array(
	//'pagina',
	//'menu',
	//'meta',
	//'categoria',
	//'dbo_slider',
	//'dbo_slider_slide',
//);

?>