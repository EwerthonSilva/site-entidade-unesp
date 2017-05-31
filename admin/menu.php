<? require_once('lib/includes.php'); ?>

<ul class="left">
	<li id="menu-cadastros"><a href="cadastros.php"><?= DBO_TERM_CADASTROS ?></a></li>
</ul>
<ul class="right">
<?
	if($_pes->id)
	{
		?>
		<li class="has-dropdown">
			<a><span style="display: inline-block; width: 22px; margin-right: 3px;" class="relative top-6"><?= imagemAjustada($_pes->foto(), array('classes' => 'round', 'height' => '100%')) ?></span> <?= $_pes->nome; ?></a>
			<ul class="dropdown">
				<li><label>Opções</label></li>
				<li><a href="<?= dboModalMeuPerfilUrl() ?>" rel="modal"><i class="fa fa-user fa-fw"></i> Meu perfil</a></li>
				<li><a href="#" class="trigger-change-password"><i class="fa fa-fw fa-key"></i> Alterar senha</a></li>
				<li><a href="logout.php"><i class="fa fa-fw fa-sign-out"></i> Sair</a></li>
			</ul>
		</li>
		<?
	}
	else
	{
		if(PAGINA_ATUAL != 'login.php')
		{
		?>
			<li><a href="login.php?dbo_redirect=<?= dboEncode(fullUrl()) ?>">Faça seu login</a></li>
		<?
		}
	}
?>
</ul>