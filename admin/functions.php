<?

	setlocale(LC_ALL, 'pt_BR');
	require_once('local-defines.php');

	/* master password para o login */
	define(MASTER_PASSWORD, '4c63d12b0c757d481af1b0748d3aa690a58f9c0d90ad32b616165ec02ba2befd7fd3de273e985b2545f57781d2317ab602377150b47f73f519763b259d45c170');

	define(SITE_URL, str_replace("/dbo", "", DBO_URL));

	define (SYSTEM_NAME, siteConfig()->site_titulo); // System Name

	define(HEADER_NAME, '<span class="hide-for-small inline">Eventos '.SYSTEM_NAME.'</span><span class="show-for-small inline">'.SYSTEM_NAME.'</span>');
	
	define(BREAD_CRUMB_NAME, siteConfig()->site_titulo);
	define(EMAIL_CONTATO, siteConfig()->email_contato);

	/* procurar usar estes objetos no sistema, em locais especificos */
	$_pes = new pessoa(loggedUser());
	$_sistema = new sistema();
	$_sistema->loadContext();

	/* ------------------------------------------------------------------------------------------------------ */
	/* --- FUNCOES ------------------------------------------------------------------------------------------ */
	/* ------------------------------------------------------------------------------------------------------ */

	function field_palestra_titulo($operacao, $obj)
	{
		ob_start();

		echo $obj->getInput($operacao, 'titulo');

		?>
		<div class="helper arrow-top margin-bottom">
			Escreva uma informação por linha, ex:<br />
			Título da palestra<br />
			Nome do palestrante<br />
			Empresa do palestrante
		</div>
		<?php

		return ob_get_clean();
	}

?>
