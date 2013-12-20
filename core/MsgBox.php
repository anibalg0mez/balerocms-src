<?php

/**
 *
 * msgBox.php
 * (c) Mar 2, 2013 lastprophet 
 * @author Anibal Gomez (lastprophet)
 * Balero CMS Open Source
 * Proyecto %100 mexicano bajo la licencia GNU.
 * PHP P.O.O. (M.V.C.)
 * Contacto: anibalgomez@icloud.com
 *
**/

class MsgBox {
	
	/**
	 * @author anibal gomez (lastprophet)
	 * MsgBox: Imprime mensajes de aviso respetando el modelo MVC.
	 * Ejemplo: MsgBox("ERROR", "Error al conectar a la base de datos.");
	 * 
	 */
	
	private $title = "";
	private $message = "";
	private $file = "";
	
	
	/**
	 * 
	 * @param string_type $title parametro titutlo de la caja de texto
	 * @param string $message mensaje de la caja de texto
	 */
	
	function __construct($title, $message) {
		
		/**
		 * Necesita fix, panel admin usa por default en 'themes', corregir
		 */
		
		$this->title = $title;
		$this->message = $message;
		$this->file = LOCAL_DIR . "/themes/universe/core/MsgBox/UI.html";
		$this->file = file_get_contents($this->file);
		$this->file = str_replace("{title}", $this->title, $this->file);
		$this->file = str_replace("{message}", $this->message, $this->file);
	}
	
	/**
	 * Retornamos nuestro valor.
	 * Forma de uso.
	 * Ejemplo: $objMsgBox = new MsgBox("TITUTLO", "MENSAJE");
	 *          $objMsgBox->Show();
	 */
	
	function Show() {
		return $this->file;
	}
	
}