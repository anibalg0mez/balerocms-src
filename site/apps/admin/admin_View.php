<?php

/**
* Plantilla de la clase appView para Balero CMS.
* Declare aqui todas las 'vistas'
**/

class admin_View extends configSettings {
	
	public $mod_name;
	
	/**
	 * Variable de contenido $content
	 */

	public $content = "";
	
	/**
	 * 
	 * Ésta variable es importante para recibir el menu de modulos del mal allá.
	 */
	
	public $menu;
	
	
	
	public function __construct() {
		
		$this->LoadSettings();
		
		$this->mod_name = _WELCOME . " "  . $this->user;
		
		/**
		 *
		 * Cargar menu de modulos y guardarlo en $menu ($this->menu).
		 */
		
		//$ldr = new AdminElements();
		//$this->menu = $ldr->mods_menu();
		
		//$this->menu = $menu;
		

		
	}

	/**
	 * Cargar la vista.
	 */
	
	public function Render() {
		
		/**
		 * 
		 * Diccionario (variables de la plantilla).
		 */
		
		$array = array(
				'content'=>$this->content,
				'mod_name'=>$this->mod_name,
				'mod_menu'=>$this->menu,
				'editor_headers'=>''
				);
		
		/**
		 * 
		 * Renderizamos nuestra página.
		 */

		$objTheme = new ThemeLoader(LOCAL_DIR . "/site/apps/admin/themes/default/panel.html");		
		echo $objTheme->renderPage($array);
		
	
	}
	
	
	/**
	 * Metodos
	 */
	
	public function get_themes() {
		
		$themes = array();
		
		if ($handle = opendir(LOCAL_DIR . "/themes/")) {
		
			while (false !== ($entry = readdir($handle))) {
				if(($entry != "..") && ($entry != ".")) {
					$themes[] = $entry;
				}
			}
		
		
		closedir($handle);
		}
		
		return $themes;
		
	}
	
	public function settings_view() {
		
		//$themes = array("tema1", "tema2", "tema3");
		
		/**
		 * 
		 * Sincronizar vista con el modelo para obtener datos.
		 */
		
		$model_settings = new admin_Model();
		$default_theme = $model_settings->get_default_theme();
		// url friendly prox versiones
		//$url_friendly_status = $model_settings->get_url_friendly_status();
		$pagination = $model_settings->get_pagination();
		
		$themes = $this->get_themes();
		
		$pages = array('5', '10', '15');
		
		$form = new Form("index.php?app=admin");
		$form->Label("<h3>"._APPEARANCE."</h3>");
		$form->DropDown($themes, _THEME. ": ", "themes", $default_theme); // universe (selected)
		$form->Label("<h3>"._PAGINATION."</h3>");
		$form->DropDown($pages, "", "pages", $pagination);
		$form->Label("<h3>"._CONFIG."</h3>");
		$form->TextField(_TITLE, "title", $this->title);
		$form->TextField(_TAGS, "keywords", $this->keywords);
		$form->TextField(_URL, "url", $this->url);
		$form->TextArea(_DESCRIPTION, "description", $this->description);
		// URLS amigables en proxima version despues de lanzamiento
// 		$form->Label("URL Amigable");
// 		if($url_friendly_status == TRUE) {
// 			$form->RadioButton("Si", "1", "url_friendly", 1); // 1 = checked
// 			$form->RadioButton("No", "0", "url_friendly");
// 		} else {
// 			$form->RadioButton("Si", "1", "url_friendly"); 
// 			$form->RadioButton("No", "0", "url_friendly", 1); // 1 = checked
// 		}
		$form->SubmitButton(_OK);
		$this->content .= $form->Show();
		
		$msg = new MsgBox(_NOTE, _BASIC_MSG);
		$this->content .= $msg->Show();
	}
	
	
} // fin clase
