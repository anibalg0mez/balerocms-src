<?php

/**
* Plantilla de la clase appModel para Balero CMS.
* Declare aqui todas las conexiones a la Base de datos.
**/

class mod_virtual_page_Model extends configSettings {
	
	public $db;
	public $editor_headers;
	public $editor;
	public $rows;
	
		
	public function __construct() {
		
		
		/**
		 * Heredar datos XML, pero como no podemos heredar lo que hay dentro de un
		 * constructor (extends), forzamos la cargar con $this->LoadSettings()
		 * 
		 */
		
		$this->LoadSettings();
		
		try {
			$this->db = new mySQL($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
		} catch(Exception $e) {
			throw new Exception($e->getMessage());
		}
		
	}
	
	public function loadModelvars() {
		
		$this->editor = "Epiceditor";
		$this->editor_headers = file_get_contents(LOCAL_DIR . "/editors/". $this->editor ."/editor_headers.html");
		
	}
	

	/**
	* Metodos
	**/
	
	public function add_page_model($virtual_title, $virtual_content, $a) {
				
		try {

			//$query = $this->db->query("INSERT INTO blog title, message, info VALUES " . $title . $message,
			//"Por " . $this->user . " @ " . date("Y-m-d H:i:s"));
			
			$date = date("Y-m-d");
	
			$query = $this->db->query("INSERT INTO virtual_page (virtual_title, virtual_content, date, active, visible) VALUES ('".$virtual_title."', '".$virtual_content."', '".$date."', '" . $a ."', '1')");
			
		} catch (Exception $e) {
			$e->getMessage();
		}
		
		
	}
	
		
	/**
	 *
	 * Este metodo es importante si queremos que nuestro total de resultados
	 * se muestre en la barra de titulo de modulos, ejemplo.
	 * Blog (17) = El modulo blog tiene 17 registros.
	 * @param unknown_type $mod_name
	 * @return number|unknown
	 */
	
	public function get_regs() {
	
		/**
		 * Obtener numero de registros
		 **/
	
		/**
		 * Consultar tabla y obtener número de registros (colocar nombre de la tabla)
		 **/
	
		$this->db->query("SELECT * FROM virtual_page");
		$regs_result = $this->db->num_rows();
	
		/**
		 * Retornar número real de registros para el titulo de
		 * el módulo de admin.
		 **/
	
		if($regs_result == 0) {
			return 0;
		} else {
			return $regs_result;
		}
	
	
	}
	
	
	public function site_map_model() {
		
		$this->db->query("SELECT * FROM virtual_page");
		$this->db->get();
		$this->rows = $this->db->rows;
		
	}
	
	public function return_virtual_title($id) {

		$this->db->query("SELECT * FROM virtual_page WHERE id='$id'");
		$this->db->get();
		
		foreach ($this->db->rows as $row) {
			$virtual_title = $row['virtual_title'];
		}
		
		return $virtual_title;
		
	}
	
	
	public function return_virtual_content($id) {
	
		$query = "SELECT * FROM virtual_page WHERE id='$id'";
		
		$this->db->query($query);
		$this->db->get();
		
		foreach ($this->db->rows as $row) {
			$virtual_content = $row['virtual_content'];
		}
	
		return $virtual_content;
	
	}
	
	public function return_value($id) {
	
		$this->db->query("SELECT * FROM virtual_page WHERE id='$id'");
		$this->db->get();
	
		if(count($this->db->rows) == 0)  {
			throw new Exception(_ID_DONT_EXIST);
		}
		
		foreach ($this->db->rows as $row) {
			$active = $row['active'];
		}
	
		return $active;
	
	}
	
	public function delete_page_confirm_model($id) {
		try {
			$this->db->query("DELETE FROM virtual_page WHERE id = '$id'");
		} catch (Exception $e) {
			throw new Exception(_ERROR_DELETING_PAGE . " " . _ID_DONT_EXIST . $e->getMessage());
		}
		
	}
	
	public function update_virtual_content($id, $title, $content, $active) {
		$this->db->query("UPDATE virtual_page SET virtual_title = '$title', virtual_content = '$content', active = '$active' WHERE id = '$id'");	
	}
	
	# Método destructor del objeto
 	public function __destruct() {
 		unset($this);
 	}	
 	
 	
}
	
