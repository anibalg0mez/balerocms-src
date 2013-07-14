<?php

/**
 *
 * view.php
 * (c) Mar 2, 2013 lastprophet 
 * @author Anibal Gomez (lastprophet)
 * Balero CMS Open Source
 * Proyecto %100 mexicano bajo la licencia GNU.
 * PHP P.O.O. (M.V.C.)
 * Contacto: anibalgomez@icloud.com
 *
**/

class installer_View extends configSettings {
	
	
	/**
	 * Variable de contenido $content
	 */

	public $content = "";
	
	
	public function __construct() {
		
		$this->LoadSettings(); //cargar datos XML
		
	}

	/**
	 * Cargar la vista.
	 */
	
	public function Render() {
		
		/**
		 *
		 * @var unknown_type Bolero CMS.
		 * Creamos nuestro diccionario desde la vista.
		 * Sintaxis de $array:
		 * 		Primer y último valor sin coma al final:
		 * 		'etiqueta' => 'valor'
		 * 		Los demás valores:
		 * 		'etiqueta' => 'valor',
		 */
		
		$array = array(
				'title'=>$this->title,
				'keywords'=>$this->keywords,
				'description'=>$this->description,
				'content'=>$this->content,
				'WARNING_MESSAGE'=>'NO PUEDO SELECCIONAR LA BASE DE DATOS',
				'virtual_pages'=>''
				);
		
		/**
		 * 
		 * Renderizamos nuestra página.
		 */

		$objTheme = new ThemeLoader(LOCAL_DIR . "/themes/universe/main.html");		
		echo $objTheme->renderPage($array);
		
	
	}
	
	
	/**
	 * Metodos
	 */
	
	/**
	 * Formulario de datos del portal
	 */
	
	public function formSiteInfo() {
		
		$adminInfo = new Form("index.php?app=installer&sr=formSiteInfo");
		$adminInfo->TextField(_TITLE, "title", $this->title);
		$adminInfo->TextField(_URL, "url", $this->url);
		$adminInfo->TextArea(_DESCRIPTION, "description", $this->description);
		$adminInfo->TextField(_TAGS, "keywords", $this->keywords);
		$adminInfo->SubmitButton(_OK);
		
		$portalMsgBox = new MsgBox(_SITE_INFO, $adminInfo->Show());
		$this->content .= $portalMsgBox->Show();
		
	}
	
	/**
	 * Formulario de datos de administrador
	 */
	
	public function formadminInfo() {
		
		$adminInfo = new Form("index.php?app=installer&sr=formadminInfo");
	
		$adminInfo->TextField(_ADMIN, "username", $this->user);
		$adminInfo->PasswordField(_PASS, "passwd", "");
		$adminInfo->PasswordField(_RETYPE_PASS, "passwd2", "");
		$adminInfo->TextField(_NAME, "firstname", $this->firstname);
		$adminInfo->TextField(_LAST_NAME, "lastname", $this->lastname);
		$adminInfo->TextField(_EMAIL, "email", $this->email);
		$adminInfo->CheckBox(_NEWSLETTER, "newsletter", 1);
		$adminInfo->SubmitButton(_OK);
		
		$admMsgBox = new MsgBox(_ADMIN_CONFIGURATION, $adminInfo->Show());
		$this->content .= $admMsgBox->Show();
		
	}
	
	/**
	 * Formulario de datos de la BD
	 */
	
	public function formDBInfo() {
		
		$DBform = new Form("index.php?app=installer&sr=formDBInfo");
		// Etiqueta (opcional) // Nombre //Valor
		// Ejemplo:
		// Inserte nombre // btnSubmit // Valor inicial
		$DBform->TextField(_DB_HOST, "dbhost", $this->dbhost);
		$DBform->TextField(_DB_USER, "dbuser", $this->dbuser);
		$DBform->PasswordField(_DB_PASS, "dbpass", $this->dbpass);
		$DBform->TextField(_DB_NAME, "dbname", $this->dbname);
		$DBform->SubmitButton(_SEND);
		
		
		$DBMsgBox = new MsgBox(_DB_CONFIG, $DBform->Show());
		$this->content .= $DBMsgBox->Show();
		
	}
	
	public function installButton() {

		try {		
	
			if(isset($_POST['submit']) && empty($_POST['passwd'])) {
				throw new Exception();
			}
			
			if(empty($this->pass)) {
				throw new Exception();
			}
			
			$install = new Form("index.php?app=installer&sr=progressBar");
			$install->SubmitButton(_INSTALL_TITLE);
		
			$iMsgBox = new MsgBox(_INSTALL_BUTTON, $install->Show());
			$this->content .= $iMsgBox->Show();
	
		} catch(Exception $e) {
			$this->content .= $this->tips_messages();
		}
		
	}
	
	public function tryButton() {
	
		$install = new Form("index.php?app=installer&sr=tryAgain");
		$install->SubmitButton("FORCE INSTALL");
	
		$iMsgBox = new MsgBox(_ERROR_INSTALLING, $install->Show());
		$this->content .= $iMsgBox->Show();
	
	}
		
	/**
	 * vistas de modelo
	 */

	public function progressBar() {
		
		$loading = file_get_contents(LOCAL_DIR . "/site/apps/installer/html/progress-bar/UI.html");
		echo $loading;
		
	}
	
	public function tips_messages() {
			
		$msg = new MsgBox(_NOTE, _INSTALLER_TIP1);
		$this->content .= $msg->Show();
			
	}
	
	public function unknow_database_error() {
			
		$msg = new MsgBox(_DB_DONT_EXIST, _PLEASE_CREATE_DATABASE);
		$this->content .= $msg->Show();
			
	}
	
	public function unknow_database_connect() {
	
			$msg = new MsgBox(_INSTALLER_ERROR, _INSTALLER_ERROR_MESSAGE);
		$this->content .= $msg->Show();
		
	}
	
	public function form_field_error($e) {
	
	$msg = new MsgBox(_ADMIN_CONFIGURATION, _CHECK_FIELDS . $e);
		$this->content .= $msg->Show();
	
	}
	
	public function file_error($e) {
	
		$msg = new MsgBox(_PERMISSIONS_ERROR, _PERMISSIONS_ERROR_MESSAGE . $e);
		$this->content .= $msg->Show();
	
	}

	public function create_db_error($e) {
		
		$msg = new MsgBox(_ERROR_CREATING_DATABASE, _ERROR_CREATING_DATABASE_MESSAGE . " " . $e);
		$this->content .= $msg->Show();
		
	}
	
	
} // fin clase
