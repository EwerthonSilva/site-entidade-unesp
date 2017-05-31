<?
	require_once('admin/lib/includes.php');
//	require_once('auth.php');

	function getCloud($texto, $minFontSize = 10, $maxFontSize = 45)
	{

		$freqData = array();

		//palavras que não devem aparecer na frequencia
		$word_black_list = array(

			'a','as','ao','aos','à','e','é','i','o','os','u','array','ante','após','até','apos','bem','com','como','contra','da','das','de','di','do','dos','du','desde','em','entre','muito','na','no','nos','nas','ou','para','perante','por','pois','que','são','ser','se','sem','sob','sobre','trás','tras','uma','um','umas','uns','o','os','que','de','dos','da','e','a','com','um','mais','uma','por','mesmo','para','n','como','maior','muito','sempre','es','p','f','at','vezes','espa','poderiam','acho','outra','outros','rios','quem','minha','outro','aten','servi','sugest','pelo','iguais','não','já','ficar','ela','isso','continuam','sendo','foram','ter','ficou','só','há','dia','sei','deve','muitas','cima','quando','uso','comprei','quanto','sugiro','está','estão','além','tipo','temos','haver','causa','dão','também','tinha','seria','mas','coisa','meio','quase','feito','feitos','muita','pouca','tem','alguns','parte','outras','pouco','mesma','demais','nÃo','essa','esta','melhor','melhores','menos','desejar','deveria','assim','deixa','deveriam','principalmente','acredito','algumas','seriam','estivessem','todas','parecem','foi','sti',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',

			'cantina',
		);

		foreach(str_word_count($texto, 1, "ÁÀÂÄĂĀÃÅĄÆĆĊĈČÇĎĐÐÉÈĖÊËĚĒĘƏĠĜĞĢáàâäăāãåąæćċĉčçďđðéèėêëěēęəġĝğģĤĦIÍÌİÎÏĪĮĲĴĶĻŁŃŇÑŅÓÒÔÖÕŐØƠŒĥħıíìiîïīįĳĵķļłńňñņóòôöõőøơœŔŘŚŜŠŞŤŢÞÚÙÛÜŬŪŮŲŰƯŴÝŶŸŹŻŽŕřśŝšşßťţþúùûüŭūůųűưŵýŷÿźżž") as $word)
		{
			$word = strtolower($word);

			// For each word found in the frequency table, increment its value by one
			if(!in_array($word, $word_black_list) && strlen($word) > 2)
			{
				array_key_exists($word, $freqData)?$freqData[$word]++:$freqData[ $word ] = 0;
			}
		}

		$minimumCount = min( array_values( $freqData ) );
		$maximumCount = max( array_values( $freqData ) );
		$spread       = $maximumCount - $minimumCount;
		$cloudHTML    = '';
		$cloudTags    = array();

		$spread == 0 && $spread = 1;

		natsort($freqData);
		$freqData = array_reverse($freqData);

		foreach($freqData as $tag => $count)
		{
			$size = $minFontSize + ( $count - $minimumCount ) * ( $maxFontSize - $minFontSize ) / $spread;
			if(intval($size) > $minFontSize) //despreza os que com menor frequencia... deixar o esquema enxuto.
			{
				$cloudTags[] = '<span data-weight=\''.intval($size).'\' style="font-size:'.floor($size).'px'.'" rel='.$tag.' class="cloud-item" title="\''.$tag.'\' retornou '.($count+1).' ocorrência'.(($count>1)?('s'):('')).'">'
				. htmlspecialchars( stripslashes( $tag ) ) . '</span>';
			}
		}

		return join( "\n", $cloudTags ) . "\n";
	}

	function makeBar($max, $parte, $all, $params = array())
	{
		$width = $parte*100/$max;
		$percent = $parte*100/$all;
		?>
		<div class='bar-full'>
			<div class='bar' style='width: <?= $percent ?>%'><span class='valor'><?= $parte ?> (<?= number_format($percent, 1, ',', '.') ?>%)</span></div>
		</div>
		<?
	}

?>
<!doctype html>
<html dir="ltr" lang="pt-BR">
<head>
	<meta name="robots" content="noindex">
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title>STI - FCFAR - Relatórios</title>
	<meta name="description" content="">
	<meta name="author" content="José Eduardo Biasioli">

	<script src="js/jquery.js"></script>
	<script type="text/javascript" charset="utf-8" src='js/jquery.awesomeCloud.js'></script>
	<script type="text/javascript" charset="utf-8" src='js/jquery.highlight.js'></script>

	<style>

	/* new clearfix */
	.clearfix:after,
	.cf:after { visibility: hidden; display: block; font-size: 0; content: " "; clear: both; height: 0; }
	* html .clearfix,
	* html .cf { zoom: 1; } /* IE6 */
	*:first-child+html .clearfix,
	*:first-child+html .cf { zoom: 1; } /* IE7 */

	html, body { color: #333; margin: 0; padding: 0; font-family: Arial, Trebuchet MS, Tahoma, Verdana, Sans-serif; font-size: 13px; padding: 10px;  }
	body { padding-bottom: 100px; }

	* { box-sizing: border-box; -ms-box-sizing: border-box; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; }

	.header-relatorio { padding: 10px; border-bottom: 3px solid #333; position: relative; }
	.header-relatorio .logo-unesp { position: absolute; bottom: 10px; right: 10px; width: 100px; }

	.header-relatorio h1,
	.header-relatorio h2,
	.header-relatorio h3 { text-align: center; clear: both; }

	.corpo-relatorio { width: 960px; margin: 0 auto; }

	table,
	tr,
	td,
	th { border-collapse: collapse; text-align: left; vertical-align: top; font-size: 13px; }
	td { border-bottom: 1px solid #CCC; padding: 0.5em 1em; }
	th { border-bottom: 1px solid #CCC; padding: 0.5em 1em; text-transform: uppercase; }

	.corpo-relatorio h1 { margin-bottom: 40px; }
	.corpo-relatorio small { font-size: 16px; color: #999; }

	.cloud-item { display: inline-block; padding-right: 0.3em; padding-left: 5px; cursor: pointer; border-radius: .3em; }
	.cloud-item:hover,
	.cloud-item.active { background: #DDD; }
	.cloud-handler { position: relative; }
	.cloud-handler h3 { display: block; padding: 10px 40px; margin: 0; border: 1px solid #999; border-right: 1px solid #FFF; border-top: 1px solid #FFF; margin-bottom: -2px; background: #FFF; float: right; }
	.wrapper-cloud { border: 1px solid #999; margin: 0 auto; padding: 30px; }

	.corpo-relatorio .wrapper-textual {  }
	.corpo-relatorio .wrapper-textual .highlight { background: yellow; }
	.corpo-relatorio .wrapper-textual p:first-child { border-top: 1px solid #DDD; }
	.corpo-relatorio .wrapper-textual .item-textual { display: block; border-bottom: 1px solid #DDD; margin: 0; padding: 1.5em 4em 1.5em 8em; position: relative; }
	.corpo-relatorio .wrapper-textual .item-textual .counter { display: block; font-size: 24px; color: #999; width: 2.5em; text-align: right; left: 0; position: absolute; top: 50%; height: 24px; line-height: 24px; margin-top: -12px; }

	.linha-bar { }
	.linha-bar .label { width: 300px; text-align: right; float: left; padding-right: 20px; font-size: 16px; line-height: 40px; }
	.linha-bar .wrapper-bar { width: 550px; float: left; height: 40px; padding: 5px 0; }
	.linha-bar .wrapper-bar .bar-full { height: 100%; line-height: 30px;  }
	.linha-bar .wrapper-bar .bar-full .bar { white-space: nowrap; padding-left: 10px; position: relative; border-top: 30px solid #DDD; }
	.linha-bar .wrapper-bar .bar-full .bar .valor { position: absolute; right: -200px; display: block; width: 190px; top: -30px; }

	.arrow-box-up { position: relative; background: #333333; padding: 15px; color: #FFF; text-decoration: none;	}
	.arrow-box-up:hover { background: #000;	}
	.arrow-box-up:after { bottom: 100%; border: solid transparent; content: " "; height: 0; width: 0; position: absolute; pointer-events: none;	}
	.arrow-box-up:after { border-bottom-color: #333333; border-width: 10px; left: 50%; margin-left: -10px; }
	.arrow-box-up:hover:after { border-bottom-color: #000; }

	.arrow-box-down { position: relative; background: #333333; padding: 8px 15px; color: #FFF; text-decoration: none;	}
	.arrow-box-down:hover { background: #000;	}
	.arrow-box-down:after { top: 100%; border: solid transparent; content: " "; height: 0; width: 0; position: absolute; pointer-events: none;	}
	.arrow-box-down:after { border-top-color: #333333; border-width: 10px; left: 50%; margin-left: -10px; }
	.arrow-box-down:hover:after { border-top-color: #000; }

	.print-button { position: fixed; background: #333333; padding: 15px; color: #FFF; text-decoration: none; top: 15px; right: 25px; z-index: 1000; }
	.print-button:hover { background: #000;	}

	.nav-links { text-align: right; padding: 30px 0; }


	@media print {
		.print-block { page-break-inside: avoid; }
		.nav-links { display: none; }
		.no-print { display: none; }
	}

	/* formreset for row-item model */
	.row { clear: both; }
	.row .item { padding: 0 5px 5px 0; width: 100%; float: left; position: relative; }
	.row .item .dica { position: absolute; top: 2px; right: 19px; font-size: 11px; color: #999; text-align: right; line-height: 13px; border: 1px solid #CCC; padding: 4px; background: #FFF; border-bottom: 1px solid #FFF; -moz-border-radius: 5px 5px 0px 0px; -webkit-border-radius: 5px 5px 0px 0px; border-radius: 5px 5px 0px 0px; }
	.row .item-100 { width: 100%; }
	.row .item-80 { width: 80%; }
	.row .item-75 { width: 75%; }
	.row .item-70 { width: 70%; }
	.row .item-66 { width: 66.6%; }
	.row .item-60 { width: 60%; }
	.row .item-50 { width: 50%; }
	.row .item-40 { width: 40%; }
	.row .item-33 { width: 33.3%; }
	.row .item-30 { width: 30%; }
	.row .item-25 { width: 25%; }
	.row .item-20 { width: 20%; }
	.row .item label { display: block; padding-bottom: 0px; }
	.row .item .input input[type=text],
	.row .item .input input[type=date],
	.row .item .input input[type=password],
	.row .item .input select,
	.row .item .input textarea {
		width: 100%; padding: 5px 10px; border: 1px solid #CCC; font-family: Trebuchet MS, Arial, Sans-serif; font-size: 13px; color: #777; background: #FFF;
		-webkit-border-radius: 4px;
		-moz-border-radius: 4px;
		-border-radius: 4px;
	}
	.row .item .input input[type=text].error,
	.row .item .input input[type=password].error,
	.row .item .input select.error,
	.row .item .input textarea.error {
		border: 1px solid red !important;
		background: #FEE !important;
	}
	.row .item .input input[type=text]:focus,
	.row .item .input input[type=password]:focus,
	.row .item .input select:focus,
	.row .item .input textarea:focus {
		border: 1px solid #AAA; background: #f3f3f3;
	}
	.row .item input.error,
	.row .item select.error,
	.row .item textarea.error {
		border: 1px solid red !important; background: #FEE !important;
	}
	.row .item input[type=button],
	.row .item input[type=submit] { padding: 10px 15px; }

	#info-chamado { padding: 15px; border: 1px solid #ccc; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; }
	#info-chamado span { display: inline-block; width: 140px; font-weight: bold; color: #999; }

	</style>

</head>
<body>
	<!--[if lt IE 9]> <div style='position: fixed; top: 0; left: 0; width: 100%; padding: 15px; font-size: 15px; background: #C00; text-align: center; color: #FFF; z-index: 10000;'>Aten&ccedil;&atilde;o: Sua vers&atilde;o do <b><u>Internet Explorer est&aacute; ultrapassada</u></b>. Esta p&aacute;gina n&atilde;o ir&aacute; funcionar corretamente. Por favor utilize o <a style='color: #FCC' class='chrome' href='http://chrome.google.com' target='_blank'>Google Chrome</a> ou <a style='color: #FCC' target='_blank' href='http://www.firefox.com' class='firefox'>Firefox</a>.</div> <![endif]-->
	<a href='#' class='print-button no-print'>Clique para Imprimir</a>
	<div class='header-relatorio'>
		<h1>Faculdade de Ciências Farmacêuticas - UNESP - Araraquara</h1>
		<h2>Serviço Técnico de Informática</h2>
		<h3>Sistama de Pesquisas - <?= date('d/m/Y') ?></h3>
		<img src='https://sistemas.unesp.br/images/unesp.svg' class='logo-unesp'>
	</div>

	<div class='corpo-relatorio'>

	<?

		/* parametros de filtragem das respostas */
		$pes = new pesquisa("WHERE slug = '".$_GET['pesquisa']."'");

		//pegando o total de pesquisados
		//TODO Verificar qual autenticação para contabilizar corretamente
		if($pes->temAutenticacao()==4){
			$sql = "SELECT COUNT(DISTINCT(ip_pesquisado)) AS total FROM resposta WHERE pesquisa = ".$pes->id." ".$params;
		}
		elseif (($pes->temAutenticacao()==1)||($pes->temAutenticacao()==2)||($pes->temAutenticacao()==3)) {
			$sql = "SELECT COUNT(DISTINCT(cpf)) AS total FROM resposta WHERE pesquisa = ".$pes->id." ".$params;
		}else{
			$sql = "SELECT COUNT( * ) AS total FROM resposta WHERE pesquisa = ".$pes->id." GROUP BY pergunta ORDER BY total desc";
		}

		//especial STI
		$res = mysql_query($sql);
		$lin = mysql_fetch_object($res);

		?><h1><small>Relatório de resultados de pesquisa agrupados - Total de pesquisados: <?= $lin->total ?></small><br /><?= $pes->nome ?></h1><?

		//lista primeiro todas as peguntas objetivas
		$perg = new pergunta("WHERE pesquisa = '".$pes->id."' AND tipo NOT IN ('text', 'textarea') ORDER BY order_by");
		if($perg->size())
		{
			do {
				?>
				<div class='pergunta-multipla-escolha print-block'>
					<h2><?= $perg->pergunta ?></h2>
					<?

					$total = array();

					//cria os totais iniciais como zero. e trata quebras de linha nas opcoes
					$opcoes = explode("\n", $perg->opcoes);
					foreach($opcoes as $key => $value)
					{
						$opcoes[$key] = trim($value);
						$total[trim($value)] = 0;
					}

					$resp = new resposta('WHERE pergunta = "'.$perg->id.'" '.$params);
					if($resp->size())
					{
						do {
							$total[trim($resp->resposta)]++;
						}while($resp->fetch());
					}


					//============= DEBUG ================
					/*				echo "<PRE>";
					print_r($total);
					echo "</PRE>"; */
					//============= DEBUG ================


					$max = max($total);
					$all = array_sum($total);

					foreach($total as $key => $value)
					{
						?>
						<div class='linha-bar cf'>
							<div class='label'><?= $key ?></div>
							<div class='wrapper-bar'><?$max==0 ? ($max= $all =1) : ""; makeBar($max, $value, $all)?></div>
						</div>
						<?
					}
					?></div><!-- pergunta-multipla-escolha --><?
				}while($perg->fetch());
			}


			//perguntas textuais
			$perg = new pergunta("WHERE pesquisa = '".$pes->id."' AND tipo IN ('textarea', 'text') ORDER BY order_by");
			if($perg->size())
			{
				do {
					?>
					<h2><?= $perg->pergunta ?></h2>
					<div class='wrapper-textual' id='pergunta-<?= $perg->id ?>'>
						<?
						$resp = new resposta("WHERE pergunta = '".$perg->id."' ".$params);
						if($resp->size())
						{
							$count = 1;
							$texto_full = '';

							do {
								if(trim($resp->resposta) != '')
								{
									$texto_full .= "\n\n\n".$resp->resposta;
									?>
									<p class='item-textual print-block' rel='pergunta-<?= $perg->id ?>'>
										<span class='counter'><?= $count ?></span>
										<?= $resp->resposta;?>
									</p>
									<?
									$count++;
								}
							}while($resp->fetch());
							?><div class='nav-links'><a class='back-to-top arrow-box-up' href='#pergunta-<?= $perg->id ?>'>Retornar à lista de frequência</a></div><?
						}
						//criando a wordcloud se tiver respostas
						if($count)
						{
							?>
							<div class='cloud-handler print-block'>
								<div class='arrow-box-down no-print' style='position: absolute; top: -8px; left: 35px;'>Clique nos termos para filtrar</div>
								<a name='pergunta-<?= $perg->id ?>'></a>
								<h3>Frequência de Termos</h3>
								<div class="wrapper-cloud" rel='pergunta-<?= $perg->id ?>'>
									<?= getCloud($texto_full) ?>
								</div>
							</div>
							<?
						}
						?></div><!-- wrapper-textual --><?
					}while($perg->fetch());

	}

	?>


	</div><!-- corpo-relatorio -->

	<script type="text/javascript" charset="utf-8">

	$(document).ready(function(){
/*		$('.wrapper-cloud').awesomeCloud({
			"size" : {
				"grid" : 10, // word spacing, smaller is more tightly packed
				"factor" : 1.2, // font resize factor, 0 means automatic
				"normalize" : true // reduces outliers for more attractive output
			},
			"color" : {
				"start" : "#AAA", // color of the smallest font, if options.color = "gradient""
				"end" : "#A00" // color of the largest font, if options.color = "gradient"
			},
			"options" : {
				"color" : "gradient", // if "random-light" or "random-dark", color.start and color.end are ignored
				"rotationRatio" : 0, // 0 is all horizontal, 1 is all vertical
				"printMultiplier" : 1, // set to 3 for nice printer output; higher numbers take longer
				"sort" : "highest" // "highest" to show big words first, "lowest" to do small words first, "random" to not care
			},
			"font" : "Trebuchet MS, Arial, sans-serif", // the CSS font-family string
			"shape" : "square" // the selected shape keyword, or a theta function describing a shape
		});
		*/

		$('.cloud-handler').each(function(){
			$(this).prependTo($(this).closest('.wrapper-textual'));
		})

		$(document).on('click', '.cloud-item', function(){

			var clicada = $(this);
			var pergunta_ativa = clicada.closest('.wrapper-cloud').attr('rel');

			if(clicada.hasClass('active')){
				//reset
				$('#'+pergunta_ativa).find('p').show().removeHighlight();
				clicada.closest('.wrapper-cloud').find('.cloud-item').removeClass('active');
			}
			else {
				//reset
				$('#'+pergunta_ativa).find('p').show().removeHighlight();
				clicada.closest('.wrapper-cloud').find('.cloud-item').removeClass('active');

				//hightlight
				clicada.addClass('active');
				$('#'+pergunta_ativa).find('p').highlight(clicada.attr('rel'));
				$('#'+pergunta_ativa).find('p').each(function(){
					if($(this).find('.highlight').length){

					}
					else {
						$(this).hide();
					}
				});
			}
		})

		$('.print-button').click(function(e){
			e.preventDefault();
			window.print();
		})
	}) //doc.ready
	</script>
</body>
</html>
