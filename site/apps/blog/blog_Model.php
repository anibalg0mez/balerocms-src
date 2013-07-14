<?php

/**
* Plantilla de la clase appModel para Balero CMS.
* Declare aqui todas las conexiones a la Base de datos.
**/

class blog_Model extends configSettings {
	
	/**
	* Variables globales
	**/
	
	public $result;
	public $db;

	public $dbhost;
	public $dbuser;
	public $dbpass;
	public $dbname;
	
	public $prueba;
	public $rows; // pasar variable a vista
	

	/**
	* Conectar a la base de datos en el constructor.
	**/
	
	public function __construct() { 
		
		$this->LoadSettings();
		
		try {
			$this->db = new mySQL($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
		} catch(Exception $e) {
			throw new Exception($e->getMessage());
		}
		
		
	}
	
	public function limit() {
		
		$admin_god = 1;
		
		$this->db->query("SELECT * FROM custom_settings WHERE id = '$admin_god'");
		$this->db->get();
		
		foreach ($this->db->rows as $row) {
			$limit = $row['pagination'];
		}
		
		/**
		 * Siempre (siempre) debemos de matar la variable $rows despues de una consulta,
		 * para limpiar los datos y esten limpios en la siguiente consulta. 
		 */
		
		unset($this->db->rows);
		return $limit;
		
	}
	
	public function theme() {
		
		$admin_god = 1;
		
		$this->db->query("SELECT * FROM custom_settings WHERE id = '$admin_god'");
		$this->db->get();
		
		foreach ($this->db->rows as $row) {
			$theme = $row['theme'];
		}
		
		/**
		 * Siempre (siempre) debemos de matar la variable $rows despues de una consulta,
		 * para limpiar los datos y esten limpios en la siguiente consulta.
		 */
		
		unset($this->db->rows);
		return $theme;
		
	}
	
	public function loadModelvars() {
		
		$this->rows = $this->db->rows;
		
	}
	
	public function get_post($min, $max) {
				
		
			$this->db->query("SELECT * FROM blog LIMIT $min, $max");
			$this->db->get(); // cargar la variable de la clase $this->db->rows[] (MySQL::rows[]) con datos.
			
			$this->rows = $this->db->rows;
			
			
			//				recorrer datos almacenados en $rows[]
			//				lo hacemos desde la vista:
			//foreach ($this->db->rows as $row) {
				//echo $row['id'] . $row['title'];
			//}
			
			/**
			 * Siempre (siempre) debemos de matar la variable $rows despues de una consulta,
			 * para limpiar los datos y esten limpios en la siguiente consulta.
			 */
			
			unset($this->db->rows);
		
	}
	
	
	public function total_post() {
		$this->db->query("SELECT * FROM blog");
		$this->db->get();
		$rows = $this->db->num_rows();
		unset($this->db->rows);
		return $rows;
	}
	

	/**
	* Metodos
	**/
		
	public function full_post_model($id) {
		
		$result = array();
		
		try {
		$this->db->query("SELECT * FROM blog WHERE id = '$id'");
		$this->db->get();
		if(empty($this->db->rows)) {
			throw new Exception(_GET_POST_ERROR);
		}
		$result = $this->db->rows;
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
		
		unset($this->db->rows);
		return $result;
		
	}
	
	# Método destructor del objeto
 	public function __destruct() {
 		unset($this);
 	}
	
	
	
}
