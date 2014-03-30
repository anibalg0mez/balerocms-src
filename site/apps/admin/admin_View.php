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
	
	public $languages;
	
	public function __construct() {
		
		$this->LoadSettings();
		
		$this->mod_name = "Settings";
		
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
				'basepath'=>$this->basepath,
				'username'=>$this->user,
				'email'=>$this->email
				);
		
		/**
		 * 
		 * Renderizamos nuestra página.
		 */

		$objTheme = new ThemeLoader(APPS_DIR . "admin/panel/main_dashboard.html");		
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
		
		$themes = $this->get_themes(); // array
		
		$pages = array('4', '8', '12');
		
		$msg = new MsgBox(_ADMIN_NOTE, _ADMIN_BASIC_MSG, "I");
		$this->content .= $msg->Show();
		
		// dynamic old way
		//$form = new Form("index.php?app=admin");
		$form = new Form();
		$dropdown_theme = $form->DropDown($themes, "themes", "class='chzn-select'", $default_theme); // default thene (selected)
		$dropdown_pagination = $form->DropDown($pages, "pages", "class='chzn-select'", $pagination);
		
						/**
						 * Labels
						 */
		
		$elements = array(
				
				
						/**
						 * Variables
						 */
				
						'title' => _ADMIN_GLOBAL_SETTINGS,
				
						/**
						 * Labels
						 */
						
						'lbl_settings' => _ADMIN_SETTINGS,
						'lbl_theme' => _ADMIN_THEME,
						'lbl_pagination' => _ADMIN_PAGINATION,
						'lbl_title' => _ADMIN_TITLE,
						'lbl_keywords' => _ADMIN_KEYWORDS,
						'lbl_url' => _ADMIN_URL,
						'lbl_insert' => _ADMIN_INSERT,
						'lbl_description' => _ADMIN_DESCRIPTION,
				
						/**
						 * Elements
						 */
				
						'txt_title' => $this->title,
						'txt_keywords' => $this->keywords,
						'txt_url' => $this->url,
						'txt_description' => $this->description,
						'dropdown_theme' => $dropdown_theme,
						'dropdown_pagination' => $dropdown_pagination);
		
		$frmtpl = new ThemeLoader(APPS_DIR . "/admin/panel/settings.html");
		$this->content .= $frmtpl->renderPage($elements);
		
		
		/**
		 * Render page
		 */
		
		$this->Render();

	}

	
} // fin clase
