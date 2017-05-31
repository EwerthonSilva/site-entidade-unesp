<?php

/* configurações de conexão com o banco de dados */
//define(DBO_DATABASE_LIB, 'mysqli');

/* define a senha mestra para login no site */
//define(MASTER_PASSWORD, 'e771ebe40a650de43bd9ed531015268d106700b5bda5a3a98b70d9bef1577642d13f75da34d97bd6f8ca0121019d6cacf79582b2708749e3899ba5c9da1de16b');

/* configurações do reCAPTCHA (https://www.google.com/recaptcha/) */
//define(DBO_RECAPTCHA_ACTIVE, 'production'); //true, 'production' ou false
//define(DBO_RECAPTCHA_SITE_KEY, ''); 
//define(DBO_RECAPTCHA_SECRET_KEY, '');

/* define as configurações para autenticação do google e facebook. A tabela pessoa precisa ter os campos 'google_id' e 'facebook_id' */
//define(GOOGLE_AUTH_CONFIG_JSON, '{ stringified JSON ... }');
//define(FACEBOOK_AUTH_CONFIG_JSON, '{"app_id":" ... ","app_secret":" ... "}');
//define(OAUTH_ALLOW_NEW_USERS, false); //se setado como false, só vai permitir que usuários já existentes loguem com o Google.
//define(OAUTH_NEW_USER_PERFIL, 'Cliente'); //Coloque por extenso o nome do perfil ou o id.
//define(OAUTH_SDK_URLS, 'login'); //lista, separada por virgulas, das páginas em que o SDK de OAuth deve ser carregado (sem o .php). (para evitar JS a toa)

/* chave da API do google maps (Google Maps JavaScript API) */
//define(GOOGLE_MAPS_API_KEY, '...');

/* define se os search engines podem indexar as páginas da área administrativa, padrão false */
//define(ALLOW_ADMIN_INDEXING, false);

/* define se os e-mails serão ou não enviados por SMTP */
//define(DBO_MAIL_IS_SMTP, false);

/* debuga o envio de e-mails pelo dboMail() */
//define(DBO_MAIL_DEBUG, true);

/* define a pasta da área administrativa do site. Em um contexto de sistema, deixar em branco */
//define(DBO_ADMIN_FOLDER, 'admin');

/* define a url do site */
//define(SITE_URL, preg_replace('#/admin/dbo$#is', '', DBO_URL));

/* define o editor de textos usado no admin (bugado, não usar). "content-tools" ou "tinymce" */
//define(PAGINA_EDITOR_TYPE, 'tinymce'); 

/* define uma pagina principal diferente do cadastros.php */
//define(DBO_ADMIN_INDEX, 'painel.php');

/* define a palavra principal do cockpit */
//define(DBO_TERM_CADASTROS, 'Cadastros');

/* definição de cores do sistema */
//define(PRIMARY_COLOR, '#2199e8');

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

/* Configurações padrão para o dbo-slider */
//$_system['dbo_slider']['settings'] = array(
	//'fonts' => array(
		//'Open Sans' => array(
			//'label' => 'Open Sans',
			//'weights' => array(
				//'300',
				//'400',
				//'700',
			//),
		//),
		//'Museo sans web' => array(
			//'label' => 'Museo Sans',
			//'weights' => array(
				//'100',
				//'300',
				//'500',
				//'700',
				//'900',
			//),
		//),
	//),
	//'colors' => array(
		//PRIMARY_COLOR => 'Primária',
	//)
//);

/* lista de campos que o usuário pode atualizar no perfil */
//$_system['meu_perfil']['campos'] = array(
	//'foto',
	//'nome',
	//'apelido',
	//'sexo',
	//'email',
	//'descricao',
//);

/* configurações de templates para páginas do sistema */
//$_system['pagina_tipo']['pagina'] = array(
	//'templates' => array(
		//'pagina-blank' => 'Página padrão',
		//'pagina-template-evento-ativo' => 'Página de evento',
	//),
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
	//'list_columns' => array( //lista de colunas da listagem. Para modulos extendidos, usar o prefixo "ext_" para a coluna
		//'titulo',
		//'ext_raca',
		//'ext_peso',
		//'categorias',
		//'nome_autor',
		//'data',
	//),
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

/* opções extra de estilos para o Content Tools */
/*$_system['content_tools']['styles'] = array(
	array('Citação','quote','p h1 h2 h3'),
	array('Borda','borda','img'),
);*/

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