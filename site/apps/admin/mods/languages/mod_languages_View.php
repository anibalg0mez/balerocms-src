<?php

/**
 * 
 * @author lastprophet
 *
 */

class mod_languages_View extends configSettings {
	
	public $mod_name = _NAME;
	
	/**
	 * Variable de contenido $content
	 */
	
	public $content = "";
	
	/**
	 * 
	 * Recibir el menu de módulos desde el Router.
	 */
	
	public $menu;
	
	public $min;
	
	public function __construct() {
		
		/**
		 * 
		 * Obtener los registros totales de esta tabla para colocarlos en el titulo de modulo
		 * en el panel izquierdo del menu. Ejemplo Blog (70)
		 */
		
		
		// load configSettings vars
		$this->LoadSettings();
	
		
	}
	
	/**
	 * 
	 * Methods
	 */
	
	public function setup_view() {
		
		$this->content .= $this->errorMessage(_LANG_NOTE);
		$form = new Form("index.php?app=admin&mod_controller=languages&sr=setup");
		$form->Label(_LANG_CONFIG);
		$lang_default = $this->multilang;
		
		if($lang_default == "yes") {
			$on = 1;
			$off = 0;
		} else {
			$off = 1;
			$on = 0;
		}
		
		$form->RadioButton(_LANG_ENABLED, "1", "lang_on", $on);
		$form->RadioButton(_LANG_DISABLED, "0", "lang_on", $off);
		$form->SubmitButton(_LANG_SAVE, "activate");
		
		if($this->multilang == "yes") {
		
		$form->Label(_LIST);
		
		/**
		 * dynamic array
		 */
		
		try {
		
			$mod_model = new mod_languages_Model();
			
			$lang_array = $mod_model->get_lenguages();
		
			/**
			 * convert dynamic array to simple array
			*/
		
			if(is_array($lang_array)) {
				$_lang_array = array();
				foreach ($lang_array as $row) {
					$_lang_array[] = $row['label'] . " (" . $row['code'] . ")";
				}
			} else {
				throw new Exception(_NO_LANGUAGES);
			}
			
			
			$active = 1;
		} catch (Exception $e) {
			$_lang_array = array($e->getMessage());
			$active = 0;
		}
		//print_r($lang_array);
		
		$form->DropDown($_lang_array, _LANG, "lang", "en");
		if($active) {
			$form->SubmitButton(_DELETE_LANG, "delete_language");
		}
		$form->TextField("<h2>" . _LABEL . "</h2> " . _EXAMPLE, "label", "");
		$form->Label(_LANG_CODE);
		$form->DropDown($this->lang_list(), _LANG, "code", "");
		$form->SubmitButton(_ADD, "submit");
		
		} // end if
	
		$this->content .= $form->Show();
		
		/**
		 * Render view
		 */
		
		$this->Render();
	}
	
	
	public function sucessMessage($message) {
		$v_message = new MsgBox("", $message);
		$string_var_message = $v_message->Show();
		
		/**
		 * 
		 * @$string_var_message se necesita almacenar el contenido en una
		 * variable de tipo String.
		 */
		
		$this->content .= $string_var_message;
		
	}
	
	public function errorMessage($message) {
		$v_message = new MsgBox("", $message);
		$string_var_message = $v_message->Show();
	
		/**
		 *
		 * @$string_var_message se necesita almacenar el contenido en una
		 * variable de tipo String.
		 */
		
		
		 $this->content .= $string_var_message;
	
	}

	
	public function Render() {
		
		$cfg = new configSettings();
		
		/**
		 * 
		 * Diccionario (variables de la plantilla).
		 */
		
		$array = array(
				'content'=>$this->content,
				'mod_name'=>$this->mod_name,
				'mod_menu'=>$this->menu,
				'basepath'=>$cfg->basepath
				);
		
		/**
		 * 
		 * Renderizamos nuestra página.
		 */

		$objTheme = new ThemeLoader(LOCAL_DIR . "/site/apps/admin/panel/html/panel.html");		
		echo $objTheme->renderPage($array);
		
	
	}

	public function lang_list() {
	
		$list_array = array('af',
				'sq',
				'ap',
				'hy',
				'eu',
				'bn',
				'bg',
				'ca',
				'km',
				'zh',
				'hr',
				'cs',
				'da',
				'nl',
				'en',
				'et',
				'fj',
				'fi',
				'fr',
				'ka',
				'de',
				'el',
				'gu',
				'he',
				'hi',
				'hu',
				'is',
				'id',
				'ga',
				'it',
				'ja',
				'jw',
				'ko',
				'la',
				'lv',
				'lt',
				'mk',
				'ms',
				'ml',
				'mt',
				'mi',
				'mp',
				'mn',
				'ne',
				'no',
				'fa',
				'pl',
				'pt',
				'pa',
				'qu',
				'ro',
				'ru',
				'sm',
				'sp',
				'sk',
				'sl',
				'es',
				'sw',
				'sv',
				'ta',
				'tt',
				'te',
				'th',
				'bo',
				'to',
				'tr',
				'uk',
				'ur',
				'uz',
				'vi',
				'cy',
				'xy');			
			return $list_array;
			
		}
	
}
