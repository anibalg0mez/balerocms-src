<?php

/**
* Plantilla de la clase appView para Balero CMS.
* Declare aqui todas las 'vistas'
**/

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
	
	public $objTheme;
	
	public $page;
	
	public function __construct() {
		
		$this->objTheme = new virtual_page_Model();
		$this->theme = $this->objTheme->theme();
		
		// forzar la carga de variables de config
		$this->LoadSettings();
		
	}
	
	public function print_virtual_pages_title() {
		
		$html = "";
		
		$value = $this->objTheme->get_virtual_pages();
		
		if(empty($value)) {
			return _NO_VIRTUAL_PAGES;
		}
		
		foreach ($this->objTheme->get_virtual_pages() as $page) {
			// dynamic
			//$html .= "<li><a href=\"index.php?app=virtual_page&id=".$page['id']."\">" . $page['virtual_title'] . "</a></li>";
			$html .= "<li><a href=\"./virtual_page/main/id-".$page['id']."\">" . $page['virtual_title'] . "</a></li>";
		}
		
		return $html;
		
	}
	
	public function print_virtual_pages_title_multilang($code) {
	
		$html = "";
	
		$value = $this->objTheme->get_virtual_pages_multilang($code);
	
		if(empty($value)) {
			return _NO_VIRTUAL_PAGES;
		}
	
		foreach ($this->objTheme->get_virtual_pages_multilang($code) as $page) {
			// dynamic
			//$html .= "<li><a href=\"index.php?app=virtual_page&id=".$page['id']."\">" . $page['virtual_title'] . "</a></li>";
			$html .= "<li><a href=\"./virtual_page/".$code."/id-".$page['id']."\">" . $page['virtual_title'] . "</a></li>";
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
		$lang->app = "blog"; // where is controller? -> blog_controller -> setlang
		$lang->defaultLang = $this->objTheme->getLang();
		
		$array = array(
				'title'=>$this->title,
				'keywords'=>$this->keywords,
				'description'=>$this->description,
				'content'=>$this->content,
				'virtual_pages'=>$this->print_virtual_pages_title(),
				'basepath'=>$this->basepath,
				'page'=>$this->page,
				'langs'=>$lang->langList($this->objTheme->getLangList())
				);
		
		/**
		 * 
		 * Renderizamos nuestra página.
		 */

		$objTheme = new ThemeLoader(LOCAL_DIR . "/themes/" . $this->theme . "/main.html");		
		echo $objTheme->renderPage($array);
		
	
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
