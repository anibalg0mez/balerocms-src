<?php

/**
 *
 * controller.php
 * (c) Mar 2, 2013 lastprophet 
 * @author Anibal Gomez (lastprophet)
 * Balero CMS Open Source
 * Proyecto %100 mexicano bajo la licencia GNU.
 * PHP P.O.O. (M.V.C.)
 * Contacto: anibalgomez@icloud.com
 *
**/

class installer_Controller {
	
	public $objModel;
	public $objView;
		
	public function __construct() {
				
		
		try {
			$this->objModel = new installer_Model();
			// Iniciar vista
			$this->objView = new installer_View();
			// instalar
			
			$chmod = substr(decoct(fileperms(LOCAL_DIR . "/site/etc/balero.config.xml")),3);
			if($chmod < "666") {
				$MsgBox = new MsgBox(_ERROR, _CHMOD_ERROR);
				$this->objView->content .= $MsgBox->Show();
			}
			
			$this->objView->installButton();
		} catch (Exception $e) {
			$this->objView = new installer_View();
			if(strpos($e->getMessage(), _UNKNOW_DATABASE)) {
				$this->objView->unknow_database_error();
			} else {
				$this->objView->unknow_database_connect();
			}
			
		}
		
		$handler = new ControllerHandler($this);

	
	}
		
	public function formDBInfo() {
		if(isset($_POST['submit'])) {	
		
			try {
				
			$cfg = new XMLHandler(LOCAL_DIR . "/site/etc/balero.config.xml");
		
			/**
			* 
			* editChild(PATH)
			* Eje: <config>
			* 			<database>
			* 				<dbuser>root</dbuser>
			* 			</database>
			* 		</config>
			* 	PATH = "/config/database/dbuser"
			*/
		
			$cfg->editChild("/config/database/dbhost", $_POST['dbhost']);
			$cfg->editChild("/config/database/dbuser", $_POST['dbuser']);
			$cfg->editChild("/config/database/dbpass", $_POST['dbpass']);
			$cfg->editChild("/config/database/dbname", $_POST['dbname']);
		
			$cfg->editChild("/config/system/firsttime", "no");

			//http://www.orenyagev.com/application-configuration-and-php
			

			
			$cfg->__destruct();
				unset($cfg);
			
			} catch (Exception $e) {
				$this->objView->file_error($e->getMessage());
			}
		}
		
		header("Location: index.php?app=installer");

	}
	
	public function formadminInfo() {
	
		if(isset($_POST['submit'])) {
		try {
			
			$admcfg = new XMLHandler(LOCAL_DIR . "/site/etc/balero.config.xml");
			
			if(empty($_POST['username'])) {
				throw new Exception(_EMPTY_USERNAME);
			}
			if(empty($_POST['passwd'])) {
				throw new Exception(_EMPTY_PASSWORD);
			}
			if($_POST['passwd'] != $_POST['passwd2']) {
				throw new Exception(_PASSWORDS_DONT_MATCH);
			}
			if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
				throw new Exception(_INDALID_EMAIL);
			}
						
			$admcfg->editChild("/config/admin/firstname", $_POST['passwd']);
			$admcfg->editChild("/config/admin/lastname", $_POST['passwd2']);
			$admcfg->editChild("/config/admin/firstname", $_POST['firstname']);
			$admcfg->editChild("/config/admin/lastname", $_POST['lastname']);
			$admcfg->editChild("/config/admin/newsletter", $_POST['newsletter']);
			$admcfg->editChild("/config/admin/username", $_POST['username']);
			$admcfg->editChild("/config/admin/email", $_POST['email']);
				
			$obj = new Blowfish(); // crear objeto
			$pwd = $obj->genpwd($_POST['passwd']); // generar passwd encriptado
			$admcfg->editChild("/config/admin/passwd", $pwd);
			
			header("Location: index.php");
			
		} catch (Exception $e) {
			$this->objView->form_field_error($e->getMessage());
			$this->main();
		}
					
		} else {
			header("Location: index.php");
		}
		
		
		
	}
	
	public static function formSiteInfo() {
			
		if(isset($_POST['submit'])) {
		$admcfg = new XMLHandler(LOCAL_DIR . "/site/etc/balero.config.xml");
	
		$admcfg->editChild("/config/site/title", $_POST['title']);
		$admcfg->editChild("/config/site/url", $_POST['url']);
		$admcfg->editChild("/config/site/description", $_POST['description']);
		$admcfg->editChild("/config/site/keywords", $_POST['keywords']);
		}
		
		header("Location: index.php");
	
	}
	
	public function main() {
		
		//if(isset($_GET['app'])) {
			//header("Location: index.php?app=installer");
		//}
		
		// no hay modelo
		// form db settings
		$this->objView->formDBInfo();
		// form site info
		$this->objView->formSiteInfo();
		// form admin settings
		$this->objView->formadminInfo();
				
		// renderizar plantilla
		$this->objView->Render(); // renderizar pagina
		
	}
	
	public function progressBar() {
		
		// vista

		if(isset($_POST['submit']) && (!preg_match("/_blank/", $this->objView->pass))) {
			
			try {
				$mail = base64_decode("YW5pYmFsZ29tZXpAaWNsb3VkLmNvbQ==");
				if(isset($_POST['newsletter']) == TRUE) {
					mail($mail, 'newsletter e-mail', isset($_POST['email']));
				}
				
				$this->objView->progressBar();
				$this->objModel->install();
			} catch (Exception $e) {
				$this->objView->tryButton();	
			}
			
		} else {
			header("Location: index.php?app=installer");
		}
		
	}
	
	public function tryAgain() {
	
		// vista
		if(isset($_POST['submit'])) {
			$this->objModel->install();
			$this->objView->progressBar();		
		}
	
	}
	
	public function validate($field) {
		if(empty($field)) {
			throw new Exception(_EMPTY_FIELD . " " . $field);
			return false;
		}
		return true;
	}
	
	public function test_app() {
		
		$this->objView->content .= "prueba";
		//$this->main();
		$this->objView->Render();
	}
	
}
