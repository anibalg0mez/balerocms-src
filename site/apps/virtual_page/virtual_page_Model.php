<?php

/**
* Plantilla de la clase appModel para Balero CMS.
* Declare aqui todas las conexiones a la Base de datos.
**/

class virtual_page_Model extends configSettings {
	
	/**
	* Variables globales
	**/
	
	public $result;
	public $db;

	public $dbhost;
	public $dbuser;
	public $dbpass;
	public $dbname;
	
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
	
	/**
	 * Obtener solo una pagina especifica
	 * @return array
	 */
	
	public function get_virtual_page($id) {
	
		$virtual_pages = array();
		$this->db->query("SELECT * FROM virtual_page WHERE id = '$id'");
		$this->db->get();
		$virtual_pages = $this->db->rows;
	
		unset($this->db->rows);
		return $virtual_pages;
	
	}
	
	/**
	 * Obtener todas las paginas virtuales
	 * @return array
	 */
	
			
	public function get_virtual_pages() {
		
		$virtual_pages = array();
		$this->db->query("SELECT * FROM virtual_page WHERE active = '1'");
		$this->db->get();
		
		if(empty($this->db->rows)) {
			$virtual_pages = "";
		} else {
			$virtual_pages = $this->db->rows;
		}
		
		unset($this->db->rows);
		return $virtual_pages;
		
	}

	/**
	* Metodos
	**/
		
	# Método destructor del objeto
 	public function __destruct() {
 		unset($this);
 	}
	
	
	
}
