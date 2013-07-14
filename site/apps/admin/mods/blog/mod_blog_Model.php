<?php

/**
* Plantilla de la clase appModel para Balero CMS.
* Declare aqui todas las conexiones a la Base de datos.
**/

class mod_blog_Model extends configSettings {
	
	public $db;
	public $editor_headers;
	public $editor;
	public $rows;
	
		
	public function __construct() {
		
		$this->tabla_name = "blog"; // SELECT * FROM "blog"
		
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
	
	public function add_post($title, $message) {
				
		try {

			//$query = $this->db->query("INSERT INTO blog title, message, info VALUES " . $title . $message,
			//"Por " . $this->user . " @ " . date("Y-m-d H:i:s"));
			
			$date = $this->user . " @ " . date("Y-m-d H:i:s");
	
			$query = $this->db->query("INSERT INTO blog (title, message, info) VALUES ('".$title."', '".$message."', '".$date."')");
			
		} catch (Exception $e) {
			$e->getMessage();
		}
		
		unset($this->db->rows);
		
		
	}
	
	public function get_post($min, $limit) {
				
		
			$this->db->query("SELECT * FROM blog LIMIT $min, $limit");
			$this->db->get(); // cargar la variable de la clase $this->db->rows[] (MySQL::rows[]) con datos.
			
			if(empty($this->db->rows)) {
				$this->rows = array();
			} else {
				$this->rows = $this->db->rows;
			}
			
			
			//				recorrer datos almacenados en $rows[]
			//				lo hacemos desde la vista:
			//foreach ($this->db->rows as $row) {
				//$this->content .= $row['id'] . $row['title'];
			//}
			
			unset($this->db->rows);
		
	}
	
	public function return_post_content($id) {
	
	
		$this->db->query("SELECT * FROM blog WHERE id='$id'");
		$this->db->get(); // cargar la variable de la clase $this->db->rows[] (MySQL::rows[]) con datos.
			
		$this->rows = $this->db->rows;

		$post_content = "";
			
		//				recorrer datos almacenados en $rows[]
		//				lo hacemos desde la vista:
		foreach ($this->db->rows as $row) {
			$post_content = $row['message'];
		}
		
		unset($this->db->rows);
		return $post_content;
		
	
	}
	
	public function return_post_title($id) {
	
	if(empty($id)) {
		die();
	}
		
		$this->db->query("SELECT * FROM blog WHERE id='$id'");
		$this->db->get(); // cargar la variable de la clase $this->db->rows[] (MySQL::rows[]) con datos.
			
		$this->rows = $this->db->rows;
	
		$post_title = "";
			
		
		
		
		//				recorrer datos almacenados en $rows[]
		//				lo hacemos desde la vista:
		
		if(count($this->db->rows) == 0) {
			die(_ID_DONT_EXIST);
		}
		
		foreach ($this->db->rows as $row) {
			$post_title = $row['title'];
		}
		
		return $post_title;
		unset($this->db->rows);
	
	}
	
	public function delete_query($id) {
		$objShield = new Security();
		$_id = $objShield->shield($id);
		$this->db->query("DELETE FROM blog WHERE id='$_id'");
		unset($this->db->rows);
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
	
		$this->db->query("SELECT * FROM blog");
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
		
		unset($this->db->rows);
	
	
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
	
 	public function edit_post($id, $title, $content) {
 		$this->db->query("UPDATE blog SET title = '$title', message = '$content' WHERE id= '$id'");
 	}
 	
 	public function total_rows() {
 		$this->db->query("SELECT * FROM blog");
 		$this->db->get();
 		$total = $this->db->num_rows();
 		
 		unset($this->db->rows);
 		return $total;
 	}

 	# Método destructor del objeto
 	public function __destruct() {
 		unset($this);
 	}
 	
 	
}
	
