<?



	/* BETA Stuff...  */

	//access restrictions
	$case = new Obj();
	$case->module = 'perfil';
	$case->field = 'nome';
	$case->data = 'Desenv';
	$case->interaction_type = 'update';
	$no_user_interaction[] = $case;

	$case = new Obj();
	$case->module = 'perfil';
	$case->field = 'nome';
	$case->data = 'Desenv';
	$case->interaction_type = 'delete';
	$no_user_interaction[] = $case;



	//automatic fields
	$__dbo_auto_fields = array();
	$__dbo_auto_fields[] = 'created_by';
	$__dbo_auto_fields[] = 'updated_by';
	$__dbo_auto_fields[] = 'created_on';
	$__dbo_auto_fields[] = 'updated_on';
	$__dbo_auto_fields[] = 'order_by';
	$__dbo_auto_fields[] = 'permalink';



	//custom menus
	function dboCustomMenus($key = false)
	{
		$custom_menus = array();

		//custom menus
		$cm = new Obj();
		$cm->name = "Menu Teste";
		$cm->slug = "menu_teste";
		$cm->image = "config.png";
		$cm->url = "http://www.google.com.br";
		$cm->target = "_blank";
		$cm->notification_function = "notifyTeste";
		$custom_menus[3] = $cm;

		if(sizeof($custom_menus))
		{
			if($key)
				return $custom_menus[$key];
			return $custom_menus;
		}
		return false;
	}

	//notification function example

	function notifyTeste()
	{
		return rand(1,10);
	}

?>