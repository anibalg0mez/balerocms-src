<?php

/**
* Plantilla de la clase appController para Balero CMS.
* Coloque aqui la entrada/salida de datos.
* Llame desde ésta clase los modelos/vistas 
* correspondientes de cada controlador.
**/

class virtual_page_Controller extends ControllerHandler {
	
	/**
	* Variables para heredar los métodos de el modelo y la vista.
	**/

	public $objModel;
	public $objView;
		
	/**
	* Los cargamos en el constructor
	**/

	public function __construct() {
				
		try {
			$this->objModel = new virtual_page_Model();
			$this->objView = new virtual_page_View();
		} catch (Exception $e) {
			$this->objView = new virtual_page_View();
		}
		
		$this->init($this);
	}
		
	/**
	* Método principal llamado main() (similar a C/C++)
	**/

	public function main() {
	
		try {
			
			if(isset($_GET['id'])) {
				$shield = new Security();
				$id = $shield->shield($_GET['id']);
				$query_content = $this->objModel->get_virtual_page($id);
				$md = new Markdown();
				$this->objView->content .= $md->defaultTransform($this->objView->print_virtual_page($query_content));
			} else {
				throw new Exception();
			}
			
			
		} catch (Exception $e) {
			$msgbox = new MsgBox(_VP, _VP_DONT_EXIST);
			$this->objView->content .= $msgbox->Show();
		}
		
		$this->objView->Render();	
		
	}
	
	/**
	* Métodos
	**/
		
	/**
	 * Métodos
	 **/
	
	public function init($var) {
		
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
						
					
					try {
					/**
					 * Multilgang posts view
					 */
						
					$total_rows = $this->objModel->total_pages_multilang($row['code']);
					$limit = $this->objModel->limit();
					$p = new Pagination($total_rows, $limit);
					$min = $p->min();
					$this->objModel->code = $_GET['sr'];
					if(isset($_GET['id'])) {
						$shield = new Security();
						$id = $shield->shield($_GET['id']);
						$query_content = $this->objModel->get_virtual_page_multilang($id, $row['code']);
						$this->objView->rows = $this->objModel->rows;
						$shield = new Security();
						$md = new Markdown();
						$this->objView->content .= $md->defaultTransform($this->objView->print_virtual_page($query_content));
					} else {
						throw new Exception();
					}
	
					} catch (Exception $e) {
						$msgbox = new MsgBox(_VP, _VP_DONT_EXIST);
						$this->objView->content .= $msgbox->Show();
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
	
	
}
