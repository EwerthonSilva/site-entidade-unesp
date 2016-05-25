<style>
	.on { color: darkgreen; }
	.off { color: red; }
</style>
<pre>
<?php
$extensions = array(
	'session'	=> 'PHP Session',
	'mysql'		=> 'MySQL',
	'hash'		=> 'HASH',
	'gd'		=> 'GDLib',
	'json'		=> 'JSON'
);

foreach($extensions as $extension => $desc) {
	print $desc . ': ' . (extension_loaded($extension) ? '<span class="on">Found</span>' : '<span class="off">Missing</span>') . PHP_EOL;
}

echo 'Htaccess support: '.(isset($_SERVER['HTACCESS']) ? '<span class="on">On</span>' : '<span class="off">Off</span>');

?>
</pre>