<?php

/**
 *
 * configSettings.php
 * (c) May 26, 2013 lastprophet 
 * @author Anibal Gomez (lastprophet)
 * Balero CMS Open Source
 * Proyecto %100 mexicano bajo la licencia GNU.
 * PHP P.O.O. (M.V.C.)
 * Contacto: anibalgomez@icloud.com
 *
**/

/**
 * Obtener datos de config en XML
 */

class configSettings {

	/**
	 *
	 * Propiedades XML
	 */
	
	/**
	 * DB test
	 */
	
	public $dbhost;
	public $dbuser;
	public $dbpass;
	public $dbname;
	
	/**
	 *
	 * Banderas
	 */
	
	public $firsttime;
	
	/**
	 *
	 * Datos del admin
	 */
	
	public $user;
	public $pass;
	
	public $email;
	
	public $firstname;
	public $lastname;
	
	public $title;
	public $description;
	public $url;
	public $keywords;
	
	
	/**
	 * Newsletter
	 */
	
	public $newsletter;
	
	public function __construct() {
				
		$this->LoadSettings(); // Cargar datos XML
		
	}
	
	/**
	 * 
	 * @function LoadSettings() Forzar la carga de variables de configuración.
	 * 
	 */
	
public function LoadSettings() {

	/**
	 *
	 * Leer el archivo de configuracion XML y almacenarlo en una variable.
	 * <nodo>
	 *    <subnodo>
	 * Ejemplo: Child(""nodo,"subnodo");
	 *
	 */

	//echo LOCAL_DIR . "/site/etc/balero.config.xml";

	try {
		$xml = new XMLHandler(LOCAL_DIR . "/site/etc/balero.config.xml");

		$this->dbhost = $xml->Child("database", "dbhost");
		$this->dbhost = $xml->Child("database", "dbhost");
		$this->dbuser = $xml->Child("database", "dbuser");
		$this->dbpass = $xml->Child("database", "dbpass");
		$this->dbname = $xml->Child("database", "dbname");

		$this->firsttime = $xml->Child("system", "firsttime");

		$this->user = $xml->Child("admin", "username");
		$this->pass = $xml->Child("admin", "passwd");
		$this->firstname = $xml->Child("admin", "firstname");
		$this->lastname = $xml->Child("admin", "lastname");
		$this->email = $xml->Child("admin", "email");

		$this->newsletter = $xml->Child("admin", "newsletter");
			
		$this->title = $xml->Child("site", "title");
		$this->url = $xml->Child("site", "url");
		$this->description = $xml->Child("site", "description");
		$this->keywords = $xml->Child("site", "keywords");

	} catch (Exception $e) {
		$title = "ERROR IN CLASS: " . get_class($this);
		$test = new MsgBox($title, $e->getMessage());
		$this->content .= $test->Show();
	}

}

}