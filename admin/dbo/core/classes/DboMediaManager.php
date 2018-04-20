<?php
	class DboMediaManager
	{

		//variavel que contem toda a estrutura de pastas
		var $folders = array();

		//variavel que contem somente as subpastas da pasta atual
		var $children = array();
		
		//nome da pasta atual		
		var $current_folder = '';

		//carrega as pastas
		function __construct()
		{
			$this->loadFolders();
		}

		//retorna um array contendo toda a estrutura de pastas do DboMediaManager
		function loadFolders()
		{
			$folders = (array)json_decode(meta::get('dbo_media_manager_folder_structure'), true);
			$this->folders = $folders;

			$folders = array_keys($folders);
			sort($folders);
			$this->children = $folders;

			//============= DEBUG ================
			//echo "<PRE>";
			//print_r($this->folders);
			//echo "</PRE>";
			//============= DEBUG ================
		
		}

		//retorno a estrutura de pastas em formato array
		function getFolders()
		{
			return $this->folders;
		}

		function getChildren()
		{
			return $this->children;
		}

		function setCurrentFolder($folder)
		{
			$this->current_folder = $folder;
		}

		//retorna uma string com o caminho completo para a pasta atual
		function getCurrentFolder()
		{
			return '';
		}

		//verifica se já existe uma subpasta com o nome especificado
		function folderExists($folder)
		{
			$folder = trim($folder);
			if(in_array($folder, $this->getChildren()))
				return true;
			return false;
		}

		//cria uma subpastas com o nome fornecido dentro do folder atual (current_folder)
		function createFolder($folder)
		{

		}
	}
?>