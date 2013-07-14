<?php

/**
* Plantilla de la clase appController para Balero CMS.
* Coloque aqui la entrada/salida de datos.
* Llame desde ésta clase los modelos/vistas 
* correspondientes de cada controlador.
**/

class virtual_page_Controller {
	
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
		
		// Automatizar el controlador
		$handler = new ControllerHandler($this);
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
			}
			
			
		} catch (Exception $e) {
			
		}
		
		$this->objView->Render();	
		
	}
	
	/**
	* Métodos
	**/
		
	
	
}
