<?php

/**
* Plantilla de la clase appController para Balero CMS.
* Coloque aqui la entrada/salida de datos.
* Llame desde ésta clase los modelos/vistas 
* correspondientes de cada controlador.
**/

/**
 * 
 * @author lastprophet
 * Extends ControllerHandler to multilanguage pages
 *
 */

class blog_Controller extends ControllerHandler {
	
	/**
	* Variables para heredar los métodos de el modelo y la vista.
	**/

	public $objModel;
	public $objView;
		
	public $lang;
	
	/**
	* Los cargamos en el constructor
	**/

	public function __construct() {
		
		try {
			$this->objModel = new blog_Model();
			$this->objView = new blog_View();
		} catch (Exception $e) {
			$this->objView = new blog_View();
		}
		
		// Automatizar el controlador
		//$handler = new ControllerHandler($this);
		
		$this->lang = $this->objModel->getLang();
		$this->init($this);
		
		
	}
		
	/**
	* Método principal llamado main() (similar a C/C++)
	**/

	public function main() {
		
		if(isset($_GET['id'])) {
						
			$this->objModel->get_fullpost($_GET['id']);
			$this->objView->rows = $this->objModel->rows;
			$this->objView->more = "";
			$this->objView->print_post();
			
		} else {
		
		// rows totales
		$total_rows = $this->objModel->total_post();
		
		// limpiamos para hacer otra consulta
		//unset($this->objModel->rows);
		
		// obtener limite de paginación desd las config.
		$limit = $this->objModel->limit();
		
		// traer paginador
		$p = new Pagination($total_rows, $limit);
		
		// obtener LIMIT (min)
		 $min = $p->min();
		 // obtener LIMIT (max)
		 //echo "max" .$max = $p->max();
		
		// limitar (min, limit)
			
		 /**
		  * For main() method
		  * Get default lang (from blog table) or
		  * Get multilang page (from blog_multilang)
		  */
		 
		 try {
		 	
		 	$this->objModel->code = $this->objModel->getLang();
		 	
		 	if(($this->objModel->code == "main") OR ($this->objModel->code == "")) {
		 		throw new Exception();
		 	}
		 	
		 	$this->lang = $this->objModel->code;
		 	$this->objView->lang = $this->lang;
		 	$this->objModel->get_post_multilang($min, $limit);
		 	$ldr = new autoloader("virtual_page");
		 	$vp = new virtual_page_View();
		 	$this->objView->printVirtualPages = $vp->print_virtual_pages_title_multilang($this->lang);
		 	
		 } catch (Exception $e) {
		 	$this->lang = "main";
		 	$this->objView->lang = "main";
		 	$ldr = new autoloader("virtual_page");
		 	$vp = new virtual_page_View();
		 	$this->objView->printVirtualPages = $vp->print_virtual_pages_title();
		 	$this->objModel->get_post($min, $limit); //main lang
		 }
		 	
		// resultado de la query en array
		$this->objView->rows = $this->objModel->rows;
		
		// mostrar post
		$this->objView->print_post();
		
		// barra de paginacion (dynamic url)
		//$this->objView->content .= $p->nav();
		
		// barra de paginacion (pretty url)
		$this->objView->content .= $p->pretty_nav("blog/" . $this->lang);
		
		//echo "total post" . $this->objModel->total_post();
		
		}
		
		$this->objView->Render();	
		
	}
	
	/**
	* Métodos
	**/
	
	public function init($var) {
	
		/**
		 *
		 * Controlador interno (sr) de una app (sección)
		 * Ejemplo: index.php?app=blog&sr=subrutina
		 * ==============================================
		 * v0.3+
		 * ==============================================
		 * Ejemplo: /blog/subrutina
		 */
	
		if(isset($_GET['sr'])) {
	
			/**
			 *
			 * Problem with CGI/Fast CGI as PHP Server API Fixed
			 */
				
			$sr = $_GET['sr'];
				
			if(!isset($_GET['app'])) {
				die(_GET_APP_DONT_EXIST);
			}
				
			//$class_methods = get_class_methods("appController");
			$security = new Security();
			$shield_var = $security->shield($_GET['app']);
			$class_methods = get_class_methods($shield_var . "_Controller");
			//var_dump($class_methods);
	
			
			$mods = new Modloader("languages");
			$ModLangs = array();
			$objModLangs = new mod_languages_Model();
			$ModLangs = $objModLangs->get_lenguages();
			
			foreach ($ModLangs as $row) {
				if($_GET['sr'] == $row['code']) {
					
					/**
					 * Multilgang posts view
					 */
					
					$total_rows = $this->objModel->total_post_multilang($row['code']);
					$limit = $this->objModel->limit();
					$p = new Pagination($total_rows, $limit);
					$min = $p->min();
					$this->objModel->code = $_GET['sr'];
					if(isset($_GET['id'])) {
						$this->objModel->get_fullpost_multilang($_GET['id']);
						$this->objView->rows = $this->objModel->rows;
						$this->objView->print_post();
					} else {
						$this->objModel->get_post_multilang($min, $limit);
						$this->objView->rows = $this->objModel->rows;
						$this->objView->print_post();
						$this->objView->content .= $p->pretty_nav("blog/" . $_GET['sr']);
					}

					$this->objView->Render();
					
					//echo $row['code'];
					die();
					
				}
			}
			
			foreach ($class_methods as $method_name) {

				if(($sr == $method_name)) {
				
					switch($sr) {
						// llama staticamente
						//appController::$sr();
						//appModel::$sr();
						//AppView::$sr();
	
						
						// llamar dinamicamente
						case $sr:
							$var->$sr();
							break;
						
	
					} // switch
	
				}
					
			} // for each
	
		} else {
			if((!isset($_GET['sr']))) {
				$var->main();
			}
		}
	
	} // fin de init()
	
	
	public function setlang() {
		
		date_default_timezone_set('UTC');
		$expire = date("Y-m-d", strtotime("+1 day")); // expire in 1 day
		
		$langsList = $this->objModel->getLangList();
		$lang = new Language();
		
		$value = $lang->setLang($langsList, $_GET['lang']);
		
		$this->objModel->setVirtualCookie($_SERVER['REMOTE_ADDR'], $value, $expire);
		$this->main();
		
	}
	
	public function test() {
		
		echo "test";
	}
	

}
