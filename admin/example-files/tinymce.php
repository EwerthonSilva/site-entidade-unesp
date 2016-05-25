<script type="text/javascript">
	$(document).ready(function() {
		$('textarea.tinymce').tinymce({
			// Location of TinyMCE script
			script_url : '<?= DBO_URL ?>/../js/tiny_mce/tiny_mce.js',

			// General options
			theme : "dbo",
			plugins : "autolink,advimage,advlink,paste,fullscreen,style",

			// Theme options
			theme_dbo_buttons1 : "bold,italic,strikethrough,|,bullist,numlist,|,outdent,indent,blockquote,|,pastetext,pasteword,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,|,hr,link,unlink,image,code,fullscreen",
			theme_dbo_buttons2 : "",
			theme_dbo_buttons3 : "",
			theme_dbo_buttons4 : "",
			theme_dbo_toolbar_location : "top",
			theme_dbo_toolbar_align : "left",
			theme_dbo_statusbar_location : "bottom",
			theme_dbo_resizing : false,
			theme_dbo_styles : "Teste 1=teste-1;Teste 2=teste-2",

			// Example content CSS (should be your site CSS)
			content_css : "tinymce.css",

			// Drop lists for link/image/media/template dialogs
			template_external_list_url : "lists/template_list.js",
			external_link_list_url : "lists/link_list.js",
			external_image_list_url : "lists/image_list.js",
			media_external_list_url : "lists/media_list.js",

			// Which html tags to allow
			//valid_elements : "-ul/-ol,-li",

			// Replace values for the template plugin
			template_replace_values : {
				username : "Some User",
				staffid : "991234"
			}
		});
	});
</script>