<?

/* ================================================================================================================== */
/* DBO CLASS FILE FOR MODULE 'pergunta' ========================================= AUTO-CREATED ON 19/04/2016 12:33:38 */
/* ================================================================================================================== */

/* IMPORTANT: This file is generated only in the first DBO sync, what means you should edit only via text editor. */

if(!class_exists('pergunta'))
{
	class pergunta extends dbo
	{
		/* smart constructor: will perform load() upon numeric argument and loadAll() upon string argument */
		function __construct($foo = '')
		{
			parent::__construct('pergunta');
			if($foo != '')
			{
				if(is_numeric($foo))
				{
					$this->id = $foo;
					$this->load();
				}
				elseif(is_string($foo))
				{
					$this->loadAll($foo);
				}
			}
		}

		//your methods here

		function getInput()
		{
			$retorno ='<h6>'.$this->pergunta.'</h6>';

			if($this->tipo == 'text'){
				if($this->obrigatoria){
					$retorno .= '<input type="text" name="'.makeSlug($this->pergunta).'"required>';
				}else {
					$retorno .= '<input type="text" name="'.makeSlug($this->pergunta).'">';
				}
			}
			elseif ($this->tipo == 'textarea') {
				if($this->obrigatoria){
					$retorno .= '<textarea name="'.makeSlug($this->pergunta).'" rows="10" cols="40" required></textarea>';
				}else{
					$retorno .= '<textarea name="'.makeSlug($this->pergunta).'" rows="10" cols="40"></textarea>';
				}
			}
			elseif ($this->tipo == 'select') {
				$opt = explode("\n", $this->opcoes);
				if($this->obrigatoria){
					$retorno .= '<select name="'.makeSlug($this->pergunta).'"required> <option value="">---</option>';
				}else {
					$retorno .= '<select name="'.makeSlug($this->pergunta).'"> <option value="">---</option>';
				}
				foreach ($opt as $value) {
					$retorno .= '<option value="'.$value.'">'.$value.'</option>';
				}
				if($this->permitir_outro){
					$retorno .= '<option value="outro">Outro</option>';
				}
				$retorno .= '</select>';

			}
			elseif ($this->tipo == 'radio') {
				$opt = explode("\n", $this->opcoes);
				foreach ($opt as $value) {
					$aux = uniqid();
					$retorno .= 	'<input id="'.$aux.'"type="radio" name="'.makeSlug($this->pergunta).'" value="'.$value.'"><label for="'.$aux.'"> '.$value.'</label><br />';
				}
				if($this->permitir_outro){
					$retorno .= '<input type="radio" id="'.makeSlug($this->pergunta).'-outro" name="'.makeSlug($this->pergunta).'" value="outro"><label class="radio-inline" for="'.makeSlug($this->pergunta).'-outro">Outro</label>';
				}
			}elseif($this->tipo =='checkbox'){
				$opt = explode("\n", $this->opcoes);
				$retorno .= '<ul class="small-block-grid-2">';
				foreach ($opt as $value) {
					$aux = uniqid();
					$retorno .= '<li><input id="'.$aux.'" type="checkbox" name="'.makeSlug($this->pergunta).'[]" value="'.$value.'"><label for="'.$aux.'">'.$value.'</label></li>';
				}
				if($this->permitir_outro){
					$aux = uniqid();
					$retorno .= '<li><label for="inputOutro'.preg_replace("/[^a-zA-Z0-9_]/", "", $this->pergunta).'">Outro</label><input id="inputOutro'.preg_replace(array('/[^a-zA-Z0-9]/'), "", $this->pergunta).'" type="text" name="'.makeSlug($this->pergunta).'[]" ></li>';
				}
				$retorno .= '</ul>';
			}

			if ($this->permitir_outro)
			{
				$retorno .= '<div id="inputoutro'.preg_replace("/[^a-zA-Z0-9_]/", "", makeSlug($this->pergunta)).'" class="hide"><input type="text" name="outro'.makeSlug($this->pergunta).'"></div>';
			}
			return $retorno;

		}

		function getBreadcrumbIdentifier()
		{
			return $this->pergunta;
		}

	} //class declaration
} //if ! class exists

?>
