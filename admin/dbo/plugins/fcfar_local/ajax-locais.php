<?

require_once('../../../lib/includes.php');

$loc = new local();
$tabela = $loc->__module_scheme->tabela;

$term = trim(addslashes($_GET['term']));

/* explodindo a pesquisa por espaço */
$partes = explode(" ", $term);
$terms = array();

foreach($partes as $key => $value)
{
	if(trim(addslashes($value)))
	{
		$terms[] = trim(addslashes($value));
	}
}

$sql_parts = array();
foreach($terms as $value)
{
	$sql_parts[] = " (

		l.nome LIKE '%".$value."%' OR 
		l.sigla LIKE '%".$value."%' OR
		l.nome_alternativo LIKE '%".$value."%' OR
		l.numero LIKE '%".$value."%' OR

		lp.nome LIKE '%".$value."%' OR 
		lp.sigla LIKE '%".$value."%' OR
		lp.nome_alternativo LIKE '%".$value."%' OR
		lp.numero LIKE '%".$value."%' OR

		la.nome LIKE '%".$value."%' OR 
		la.sigla LIKE '%".$value."%' OR
		la.nome_alternativo LIKE '%".$value."%' OR
		la.numero LIKE '%".$value."%'

	) ";
}

if(sizeof($sql_parts))
{
	$sql = "

		SELECT 

			CONCAT(

				IFNULL(
					CASE lp.pai
						WHEN -1 THEN ''
						ELSE CONCAT(
							(SELECT 
								CONCAT(
									CASE lavo.sigla
										WHEN '' THEN lavo.nome
										ELSE lavo.sigla
									END,
									CASE lavo.numero
										WHEN '' THEN ''
										ELSE CONCAT(' ', lavo.numero)
									END
								)
							FROM ".$tabela." lavo
							WHERE
								lavo.id = lp.pai
							),
							' - '
						)
					END,
					''
				),

				CASE l.pai
					WHEN -1 THEN ''
					ELSE CONCAT(
						(SELECT 
							CONCAT(
								CASE lpai.sigla
									WHEN '' THEN lpai.nome
									ELSE lpai.sigla
								END,
								CASE lpai.numero
									WHEN '' THEN ''
									ELSE CONCAT(' ', lpai.numero)
								END
							)
						FROM ".$tabela." lpai
						WHERE
							lpai.id = l.pai
						),
						' - '
					)
				END,

				l.nome,
				CASE l.numero
					WHEN '' THEN ''
					ELSE CONCAT(' ', l.numero)
				END

			) AS nome,

			l.id AS id

		FROM 
			".$tabela." l

		LEFT JOIN ".$tabela." lp 
			ON l.pai = lp.id
		LEFT JOIN ".$tabela." la 
			ON lp.pai = la.id 

		WHERE

		".implode(" AND ", $sql_parts)."

		ORDER BY nome

	";

	$res = dboQuery($sql);

	if(dboAffectedRows())
	{
		while($lin = dboFetchObject($res))
		{
			$resultados[] = array('id' => $lin->id, 'local' => $lin->nome);
		}
	}
	else
	{
		$resultados[] = array('id' => '-1', 'local' => "Não há locais com este nome. Entre em contato com o STI (Ramal: 4651) para informar o problema.", 'depto' => '');
	}

	echo json_encode($resultados);
}

?>