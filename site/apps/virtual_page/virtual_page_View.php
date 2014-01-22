<?php

/**
* Plantilla de la clase appView para Balero CMS.
* Declare aqui todas las 'vistas'
**/

/**
 * Multi-Language Fixes
 */

class virtual_page_View extends configSettings {
	
	public $theme;
	
	public $rows;
	
	/**
	 * Variable de contenido $content
	 */

	public $content = "";
	
	/**
	 * Variables de sistema
	 */
	
	public $virtual_pages;
	
	public $objModel;
	
	public $lang;
	
	public function __construct() {
		
		$this->objModel = new virtual_page_Model();
		$this->theme = $this->objModel->theme();
		
		// forzar la carga de variables de config
		$this->LoadSettings();
		
	}
	
	/**
	 * 
	 * @code $code: Current language running
	 * @return Virtual Pages Menú
	 * 
	 */
	
	public function virtual_pages_menu() {
		
		$html = "";

		// pass lang to class
		$this->objModel->lang = $this->lang;
		$value = $this->objModel->get_virtual_pages();
		
		if(empty($this->lang) || $this->lang == "main") {
			foreach ($value as $page) {
				// dynamic
                //$html .= "<li><a href=\"index.php?app=virtual_page&id=".$page['id']."\">" . $page['virtual_title'] . "</a></li>";
                $html .= "<li><a href=\"./virtual_page/main/id-".$page['id']."\">" . $page['virtual_title'] . "</a></li>";
			}
		} else {
			foreach ($value as $page) {
				// dynamic
				//$html .= "<li><a href=\"index.php?app=virtual_page&id=".$page['id']."\">" . $page['virtual_title'] . "</a></li>";
				$html .= "<li><a href=\"./virtual_page/".$this->lang."/id-".$page['id']."\">" . $page['virtual_title'] . "</a></li>";
			}
		}
				
		if(empty($value)) {
			return _NO_VIRTUAL_PAGES;
		}
		
		return $html;
			
	}
	
	public function print_virtual_page($db_array = array()) {
	
		if(!is_array($db_array)) {
			die();
		}
		
		$html = "";
	
		foreach ($db_array as $page) {
			$this->page = htmlspecialchars($page['virtual_title']);
			// markdown header 2
			$html .= "## " . $page['virtual_title'] . "\n";
			// markdown italic
			$html .= "*" . $page['date'] . "*\n";
			// sin formato markdown
			$html .= "" . $page['virtual_content'] . "";
		}
		
		return $html;
	
	}

	/**
	 * Cargar la vista.
	 */
	
	public function Render() {
				
		
		$lang = new Language();
		$lang->multilang = $this->multilang;
		$lang->app = "blog"; // where is controller? -> blog_controller -> setlang
		$lang->defaultLang = $this->objModel->getLang();
		
		$array = array(
				'title'=>$this->title,
				'keywords'=>$this->keywords,
				'description'=>$this->description,
				'content'=>$this->content,
				'virtual_pages'=>$this->virtual_pages_menu($lang->defaultLang),
				'basepath'=>$this->basepath,
				'page'=>$this->page,
				'langs'=>$lang->langList($this->objModel->getLangList())
				);
		
		/**
		 * 
		 * Renderizamos nuestra página.
		 */

		$objModel = new ThemeLoader(LOCAL_DIR . "/themes/" . $this->theme . "/main.html");		
		echo $objModel->renderPage($array);
		
	
	}
	
	public function print_all_pages() {
		
		$this->content .= "<h3>" . _INDEXOF . "</h3>";
		
		
		foreach ($this->rows as $row) {			
				
			try {
			$this->content .= $row['virtual_title'];
			} catch (Exception $e) {
			
			}
			
		}
		
		
	}
	
	
	/**
	 * Metodos
	 */
	
	
	
} // fin clase
