<? require_once('header.php') ?>
<?
	$_SESSION['dbomaker_controls']['show_field_basic'] = TRUE;
	$_SESSION['dbomaker_controls']['show_field_advanced'] = FALSE;
	$_SESSION['dbomaker_controls']['show_field_type'] = FALSE;
?>
<div class='toolbar'>
	<div class='bg'>
		<div class='button-sync'><span><i class="fa fa-refresh"></i> SYNC</span></div>
		<div class='button-enable-dnd'><span><i class="fa fa-arrows"></i> Drag</span></div>
		<div class='button-enable-sort'><span><i class="fa fa-sort"></i> Sort</span></div>
		<div class='button-sync-db'><span><i class="fa fa-database"></i> Sync DB</span></div>
		<div class='button-sync-all'><span><i class="fa fa-refresh"></i> Sync All</span></div>
		<div class='trash'><span>LIXEIRA</span></div>
	</div>
</div>
<div class='lixo' style='display: none;'></div>

<div id='canvas'>

	<div class='column-1'>
		<div id='wrapper-modules' class='wrapper-box'>
			<h1>Módulos</h1>
			<div class='anchor sortable sort-modules'></div>
		</div>
	</div>

	<div class='column-2'>
		<div class='center-canvas'>
			<div class='column-1'>
				<div id='wrapper-fields' class='wrapper-box'>
					<div class='anchor'></div>
				</div>
			</div>
			<div class='column-2'>
				<div id='wrapper-details' class='wrapper-box'>
					<div class='anchor'></div>
				</div>
			</div>
		</div>
	</div>

	<div class='column-3'>
	</div>

</div>

<? require_once('footer.php'); ?>