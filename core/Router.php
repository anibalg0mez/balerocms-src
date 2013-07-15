<?php

/**
 *
 * Router.php
 * (c) Feb 26, 2013 lastprophet 
 * @author Anibal Gomez (lastprophet)
 * Balero CMS Open Source
 * Proyecto %100 mexicano bajo la licencia GNU.
 * PHP P.O.O. (M.V.C.)
 * Contacto: anibalgomez@icloud.com
 *
**/

//require_once(LOCAL_DIR . "/core/Security.php");
require_once(LOCAL_DIR . "/core/boot.php");

class Router {
	
	public $local_name;
	public $app;
	
	public $message;
	
	public $lang;
	
	function __constructor() { 
	
	}
	
	function init() {
		
		/**
		 * 
		 * Cargar nucleo
		 */
		
		$init = new boot(); // cargar nucleo
		
		/**
		 * Cargar lenguaje
		 */
		
		$this->lang = new Language();
		$this->lang->init();
		
		if(file_exists(LOCAL_DIR . "/site/apps/" . "installer")) {
			
			$this->lang->init_apps_lang("installer");
			
			try {
				$xml = new XMLHandler(LOCAL_DIR . "/site/etc/balero.config.xml");
			} catch (Exception $e) {
				die(_CONFIG_FILE_ERROR);	
			}
			
			$installed = $xml->Child("system", "installed");
			
			$pos = strpos($installed, "yes");
			
			// Note our use of ===.  Simply == would not work as expected
			// because the position of 'a' was the 0th (first) character.
			if ($pos === false) {
				$ldr = new autoloader("installer"); // cargar clases para la app
				$app = new installer_Controller();
				die();
			} else {
				$ldr = new autoloader("installer"); // cargar clases para la app
				$app = new installer_View();
				$msgbox = new MsgBox(_SECURITY_LOCK, _SECURITY_LOCK_MESSAGE);
				$app->content .= $msgbox->Show();
				$app->Render();
				die();
			}
			
		}
	
		/**
		 * Router (controlador) de secciones (app)
		 */

		if(isset($_GET['app'])) {
			if(empty($_GET['app'])) {
				header("Location: index.php");
			}
			
			$security = new Security();
			$app_get = $security->shield($_GET['app']);

			//$sr = $_GET['sr'];
			
			$admin = 0;
			
			switch ($app_get) {

				/**
				 * Procesar controladores de los modulos de admin.
				 */
				
				case "admin":
				$this->admin_router();
				break;
				
				
				/**
				 * Procesar controladores de las apps (secciones).
				 */

				case $app_get:
				if(file_exists(LOCAL_DIR . "/site/apps/" . $app_get . "/" . $app_get . "_Controller.php")) {
					$this->lang->init_apps_lang($app_get);
					$ldr = new autoloader($app_get); // cargar clases para la app
					$dynamic = $app_get . "_Controller";
					$app = new $dynamic();
				}
				break;
				
			}
			
		} else {
				$ldr = new autoloader("blog"); // cargar clases para la app
				$app = new blog_Controller();
		}
		
		
	}
		
	public function admin_router() {
		
		if(!isset($_COOKIE['admin_god_balero'])) {
			if(isset($_POST['submit'])) {
				$cfg = new configSettings();
				$login = new Blowfish();
				$verify = $login->verify_hash($_POST['pwd'], $cfg->pass);
		
				if($_POST['usr'] == $cfg->user && $verify == TRUE) {
					$value = base64_encode($cfg->user . ":" . $cfg->pass);
					setcookie("admin_god_balero",$value, time()+3600*24);
					header("Location: index.php?app=admin");
				} else {
					$this->message = _LOGIN_ERROR;
				}
			}
		}
			
		if(isset($_COOKIE['admin_god_balero'])) {
			
			$cfg = new configSettings();
			$login = new Blowfish();
			
			$cookie = base64_decode($_COOKIE['admin_god_balero']);
			
			//echo $cookie;
			
			$pieces = explode(":", $cookie);
			$cookie_usr = $pieces[0];
			$cookie_pwd = $pieces[1];
			
			
			if($cfg->user == $cookie_usr && $cfg->pass == $cookie_pwd) {
				$ldr = new autoloader("admin"); // cargar clases para la app
				$this->init_mod();
			}
			
		} else {
			$cfg = new configSettings();
			$login = new Blowfish();
			$login->message = $this->message;
			echo $login->login_form("site/apps/admin/themes/default/login.html");
		}
			
	}
	
	public function init_mod() {
	
		
		
		/**
		 * Buscar en esta carpeta los modulos modloader("carpeta");
		 */
	
		if(isset($_GET['mod_controller'])) {
	
			$mod_controller_blind = new Security();
			$blind_url = $mod_controller_blind->shield($_GET['mod_controller']);
	
			switch ($blind_url) {
	
				case $blind_url:
					if(file_exists(LOCAL_DIR . "/site/apps/admin/mods/" . $blind_url)) {
					$this->lang->init_mods_lang($blind_url);
					//include_once(LOCAL_DIR . "/site/apps/admin/mods/" . $blind_url . "/lang/en.php");
					$mod_loader = new Modloader($blind_url);
					$dynamic = "mod_" . $blind_url . "_Controller";
					$admin_elements = new AdminElements();
					$title_mod_menu = $admin_elements->mods_menu();
					// cargar controlador de pagina de inicio (admin).
					$settings_controller = new $dynamic($title_mod_menu);
					} else {
						die(_CONTROLLER_NOT_FOUND);
					}
					break;
	
				default:
					if(file_exists(LOCAL_DIR . "/site/apps/admin/admin_Controller.php")) {
					// cargar lementos de admin
					$admin_elements = new AdminElements();
					$title_mod_menu = $admin_elements->mods_menu();
					// cargar controlador de pagina de inicio (admin).
					$settings_controller = new admin_Controller($title_mod_menu);
					} else {
						die(_CONTROLLER_ADMIN_NOT_FOUND);
					}
			}
	
		} else {
			if(file_exists(LOCAL_DIR . "/site/apps/admin/admin_Controller.php")) {
			// cargar lementos de admin
			$admin_elements = new AdminElements();
			$title_mod_menu = $admin_elements->mods_menu();
			// cargar controlador de pagina de inicio (admin).
			$settings_controller = new admin_Controller($title_mod_menu);
			} else {
				die(_CONTROLLER_ADMIN_NOT_FOUND);
			}
		}
	}
	
	
}
