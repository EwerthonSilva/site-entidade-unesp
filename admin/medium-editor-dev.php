<?php
	require_once('lib/includes.php');
	require_once('header.php');
	echo dboImportJs(array(
		'medium-editor',
	));
?>

<div id="medium-editor-module-catalog">
	<i class="fa fa-plus-circle font-28"></i>
</div>

<div class="row">
	<div class="large-12 columns">
		<div class="medium-editor editable medium-editor" id="editor-maneiro">
			<h2>macacos me mordam</h2>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sapiente sed at sit dolores eum laborum quasi laudantium consequatur pariatur ex nemo ea doloremque voluptate voluptas accusantium culpa error earum quidem nesciunt quas autem illum unde molestiae? Ea maiores ipsa recusandae temporibus nesciunt dolores voluptatibus aliquid similique repellat et reprehenderit nam ipsum vel dignissimos ex eos aspernatur sed natus. Incidunt repellendus cum ab animi corporis officiis reiciendis molestiae quasi quibusdam ipsum distinctio ullam modi illum id deserunt placeat deleniti nemo repellat quam porro cupiditate nostrum autem aliquid natus hic ipsam et dolor dolorem nobis vero. Recusandae eligendi delectus illum minus atque!</p>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Qui asperiores cupiditate quod architecto deleniti rerum totam cum iste accusamus vitae libero inventore. Quas voluptates inventore quod vitae nemo quaerat totam eius soluta commodi placeat adipisci nostrum dicta voluptatum similique quos repellendus quo incidunt voluptatem rerum fugiat earum ab quae obcaecati!</p>
			<div class="dbo-medium-editor-static-element" contenteditable="false" >
				<ul class="element-toolbar">
					<li><span class="trigger-delete-medium-editor-element"><i class="fa fa-times"></i></span></li>
				</ul>
				<h5 style="font-weight: bold;" contenteditable="true" data-disable-return="true">Isso não pode ser editado</h5>
				<hr>
				<div class="row">
					<div class="large-4 columns">
						<div class="editable"></div>
					</div>
					<div class="large-4 columns">
						<div class="editable"></div>
					</div>
					<div class="large-4 columns">
						<div class="editable"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="text-right">
			<div class="button radius" onClick="console.log(editor.serialize())">Serialize, bitch!</div>
		</div>
	</div>
</div>

<script>
	
	medium_editor = {
		active_editor: null,
		active_dom: null,
		catalog_timer: null,
		catalog: null,

		init: function() {
			console.log('medium editor init');
			this.catalog = $('#medium-editor-module-catalog');
			this.catalog.appendTo(document.body);
		},
	
		updateVars: function(){
			this.active_editor = editor;
			this.active_dom = editor.getSelectedParentElement();
		},

		emptyDom: function(dom){
			if(dom.innerHTML == '<br>') 
				return true;
			return false;
		},

		showModuleCatalog: function(dom){
			var dom = $(this.active_dom);
			var pos = dom.offset();
			var h = dom.outerHeight();
			this.catalog.css('top', (h - this.catalog.outerHeight())/2 + pos.top +'px').css('left', pos.left-35+'px').fadeIn('fast');
		},

		hideModuleCatalog: function(){
			if(this.catalog[0].offsetParent == null) return;
			this.catalog.fadeOut('fast');
		},
		
	};

	var editor = new MediumEditor('.editable', {
		buttonLabels: 'fontawesome',
		spellcheck: false,
		placeholder: { 
			text: 'Digite o seu texto' 
		},
	});

	function setCursorInElement(elem) {
		window.setTimeout(function() {
			var sel, range;
			if (window.getSelection && document.createRange) {
				range = document.createRange();
				range.selectNodeContents(elem);
				range.collapse(true);
				sel = window.getSelection();
				sel.removeAllRanges();
				sel.addRange(range);
			} else if (document.body.createTextRange) {
				range = document.body.createTextRange();
				range.moveToElementText(elem);
				range.collapse(true);
				range.select();
			}
		}, 1);
	}

	medium_editor.init();

	$(document).on('keyup click', '.medium-editor', function(e){
		medium_editor.updateVars();
		if(medium_editor.emptyDom(medium_editor.active_dom)){
			medium_editor.showModuleCatalog(medium_editor.active_dom);
		}
		else {
			medium_editor.hideModuleCatalog();
		}
	});

	$(document).on('click', '.trigger-delete-medium-editor-element', function(e){
		e.preventDefault();
		c = $(this);
		var ans = confirm('Tem certeza que deseja remover este elemento do seu texto?');
		if (ans==true) {
			c.closest('.dbo-medium-editor-static-element').replaceWith('<p class="dbo-medium-editor-bogus"><br></p>');
			elem = $('.dbo-medium-editor-bogus').removeClass('dbo-medium-editor-bogus');
			setCursorInElement(elem[0]);
		}
	});

</script>
<?php require_once("footer.php"); ?>