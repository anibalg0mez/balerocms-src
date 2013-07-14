<?php

/**
* Plantilla de la clase appView para Balero CMS.
* Declare aqui todas las 'vistas'
**/

class blog_View extends configSettings {
	
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
		
		$this->objTheme = new blog_Model();
		$this->theme = $this->objTheme->theme();
		
		// forzar la carga de variables de config
		$this->LoadSettings();
		
	}
	
	public function print_virtual_pages_title() {
		
		$html = "";
		
		foreach ($this->objTheme->get_virtual_pages() as $page) {
			$html .= "<li><a href=\"#\">" . $page['virtual_title'] . "</a></li>";
		}
		
		return $html;
		
	}

	/**
	 * Cargar la vista.
	 */
	
	public function Render() {

		// incluir clases fuera de este directorio		
		$ldr = new autoloader("virtual_page");
		
		// trae paginas virtuales
		$vp = new virtual_page_View();
		
		$array = array(
				'title'=>$this->title,
				'keywords'=>$this->keywords,
				'description'=>$this->description,
				'content'=>$this->content,
				'virtual_pages'=>$vp->print_virtual_pages_title()
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
		
		$word_count = 0;
		
		foreach ($this->rows as $row) {
			
			/**
			 * 
			 * Llamar la clase Markdown.
			 */
			
			$limit = 1000;
			$word_count = strlen($row['message']);
			
			try {
			$post = $this->truncate_word($row['message'], $limit);
			if($word_count > $limit) {
				$post .= "(<a href=\"index.php?app=blog&sr=full_post&id=".$row['id']."\" class=\"more\">Más...</a>)";
			}
			$render_html = Markdown::defaultTransform($post);
			$this->content .= $tips->blue($row['title']) . "<br />" . $tips->white($render_html) . "<br />" . $tips->green($row['info']);
			
			} catch (Exception $e) {
			
			}
			
		}
		
		
	}
	
	
	/**
	 * Metodos
	 */
	
	/**
	 * @truncate_word() Cortar palabras o limitar.
	 * @param String $string cadena
	 * @param INT $limit limite
	 */
	
	public function full_post_view($array) {
		
		$markdown = new Markdown();
		$box = new Tips();
		
		foreach ($array as $row) {
			$this->content .= $box->green($row['title']);
			$this->content .= $box->white($markdown->defaultTransform($row['message']));
		}
		
	}
	
	public function truncate_word($string, $limit) {
	
		$truncate_string = substr($string, 0, $limit);
	
		$new_string = $truncate_string . "...";
	
		return $new_string;
	
	}
	
	public function message($message) {
		$msg = new Tips();
		return $msg->red($message);
	}
	
	
	
} // fin clase
