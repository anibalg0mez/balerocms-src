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
			$html .= "<li><a href=\"index.php?app=virtual_page&id=".$page['id']."\">" . $page['virtual_title'] . "</a></li>";
		}
		
		return $html;
		
	}
	
	public function print_virtual_page($db_array = array()) {
	
		$html = "";
	
		foreach ($db_array as $page) {
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
				
		$array = array(
				'title'=>$this->title,
				'keywords'=>$this->keywords,
				'description'=>$this->description,
				'content'=>$this->content,
				'virtual_pages'=>$this->print_virtual_pages_title()
				);
		
		/**
		 * 
		 * Renderizamos nuestra página.
		 */

		$objTheme = new ThemeLoader(LOCAL_DIR . "/themes/" . $this->theme . "/main.html");		
		echo $objTheme->renderPage($array);
		
	
	}
	
	public function print_post() {
		
		//				recorrer datos almacenados en $rows[]
		//				lo hacemos desde la vista:
		
		// Renderizamos los post con la clase para mostrar tips.
		$tips = new Tips();
		
		// debug
		//var_dump($this->rows);
		
		foreach ($this->rows as $row) {
			
			/**
			 * 
			 * Llamar la clase Markdown.
			 */
			
			try {
			$render_html = Markdown::defaultTransform($row['message']);
			$this->content .= $tips->blue($row['title']) . "<br />" . $tips->white($render_html) . "<br />" . $tips->green($row['info']);
			} catch (Exception $e) {
			
			}
			
		}
		
		
	}
	
	
	/**
	 * Metodos
	 */
	
	
	
} // fin clase
