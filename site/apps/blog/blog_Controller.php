<?php

/**
* Plantilla de la clase appController para Balero CMS.
* Coloque aqui la entrada/salida de datos.
* Llame desde ésta clase los modelos/vistas 
* correspondientes de cada controlador.
**/

class blog_Controller {
	
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
			$this->objModel = new blog_Model();
			$this->objView = new blog_View();
		} catch (Exception $e) {
			$this->objView = new blog_View();
		}
		
		// Automatizar el controlador
		$handler = new ControllerHandler($this);
	}
		
	/**
	* Método principal llamado main() (similar a C/C++)
	**/

	public function main() {
	
		if(!isset($_GET['app'])) {
			header("Location: index.php?app=blog");
			die();
		}
		
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
		$this->objModel->get_post($min, $limit);
	
		// resultado de la query en array
		$this->objView->rows = $this->objModel->rows;
		
		// mostrar post
		$this->objView->print_post();
		
		// barra de paginacion
		$this->objView->content .= $p->nav();
		
		//echo "total post" . $this->objModel->total_post();
		
		
		$this->objView->Render();	
		
	}
	
	/**
	* Métodos
	**/
		
	public function full_post() {

		$array = array();
		$s = new Security();
		
		try {
			if(!isset($_GET['id'])) {
				throw new Exception(_WRONG_BLOG_PARAMETERS);
			}
			if(empty($_GET['id'])) {
				throw new Exception(_POST_ID_NOT_FOUND);
			}
			$_GET['id'] = $s->shield($_GET['id']);
			$array = $this->objModel->full_post_model($_GET['id']);
			$this->objView->full_post_view($array);
		} catch (Exception $e) {
			$this->objView->content .= $this->objView->message($e->getMessage());
		}
		
		$this->objView->Render();
		
	}
	
	public function test_app() {
		
		$this->objView->content .= "prueba";
	}
	
}
