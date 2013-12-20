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
		
		if(file_exists(LOCAL_DIR . "/site/apps/" . "installer")) {
			
			/**
			 * Cargar lenguaje
			 */
			
			$this->lang = new Language();
			$this->lang->init();
			$this->lang->init_apps_lang("installer");
			
			try {
				$xml = new XMLHandler(LOCAL_DIR . "/site/etc/balero.config.xml");
			} catch (Exception $e) {
				//die(_CONFIG_FILE_ERROR);
				$theme = new ThemeLoader(LOCAL_DIR . "/site/apps/installer/html/cfgFileError.html");
				echo $theme->renderPage(array(
						"msg_error"=>_CONFIG_FILE_ERROR,
						"refresh"=>_REFRESH));
				die();	
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
				unset($this->lang);
				die();
			}
			
			unset($this->lang);
			
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
				$this->admin_router(); // login inside this method
				break;
				
				case "logout";
				$this->logout();
				break;
				
				
				/**
				 * Procesar controladores de las apps (secciones).
				 */

				case $app_get:
				if(file_exists(LOCAL_DIR . "/site/apps/" . $app_get . "/" . $app_get . "_Controller.php")) {
					$ldr = new autoloader($app_get); // cargar clases para la app
					$this->lang = new Language();
					$dynamic = $app_get . "_Controller";
					$this->lang->init();
					$this->lang->init_apps_lang($app_get);
					$this->lang->app = $app_get;
					$app = new $dynamic();	
					unset($this->lang);
				}
				break;
				
			}
			
		} else {
			
			/**
			 * default app or home
			 */
			
				$ldr = new autoloader("blog"); // cargar clases para la app
				
				/**
				 * First Load Language and wait
				 */
				
				$this->lang = new Language();
				$this->lang->init();
				$this->lang->init_apps_lang("blog");
				$this->lang->app = "blog";
				
				/**
				 * Then load app controller
				 */
				
				$app = new blog_Controller();
				
				/**
				 * Kill lang
				 */
				
				unset($this->lang);
				
		}
		
		
	}
		
	/**
	 * Router has the login page inside BlowFish class
	 */
	
	public function admin_router() {
		
		$this->lang = new Language();
		$this->lang->init();
		$this->lang->init_apps_lang("admin");
		$this->lang->app = "admin";
		
		if(!isset($_COOKIE['admin_god_balero'])) {
			if(isset($_POST['submit'])) {
				$cfg = new configSettings();
				$login = new Blowfish();
				$verify = $login->verify_hash($_POST['pwd'], $cfg->pass);
		
				if($_POST['usr'] == $cfg->user && $verify == TRUE) {
					$value = base64_encode($cfg->user . ":" . $cfg->pass);
					setcookie("admin_god_balero",$value, time()+3600*24);
					//header("Location: index.php?app=admin");
					header("Location: ./admin");
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
			$login->basepath = $cfg->basepath;			
			echo $login->login_form(LOCAL_DIR . "/site/apps/admin/panel/html/login.html");
		
		}
			
		unset($this->lang);
		
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
					$this->lang = new Language();
					$this->lang->init();
					$this->lang->app = $blind_url;
					$this->lang->init_mods_lang($blind_url);
					//include_once(LOCAL_DIR . "/site/apps/admin/mods/" . $blind_url . "/lang/en.php");
					$dynamic = "mod_" . $blind_url . "_Controller";
					$mod_loader = new Modloader($blind_url);
					$admin_elements = new AdminElements();
					$title_mod_menu = $admin_elements->mods_menu();
					// cargar controlador de pagina de inicio (admin).
					$settings_controller = new $dynamic($title_mod_menu);
					} else {
						die(_CONTROLLER_NOT_FOUND);
					}
					unset($this->lang);
					break;
	
			}
	
		} else {
			
			/**
			 * Init admin app controller
			 */
			
			if(file_exists(LOCAL_DIR . "/site/apps/admin/admin_Controller.php")) {
				
				/**
				 * Load lang and wait
				 */
				
				$this->lang = new Language();
				$this->lang->app = "admin";
				$this->lang->init();
				$this->lang->init_apps_lang("admin");
			
				/**
				 * Load panel and admin controller
				 */
				
			$admin_elements = new AdminElements();
			$title_mod_menu = $admin_elements->mods_menu();
			// cargar controlador de pagina de inicio (admin).
			$settings_controller = new admin_Controller($title_mod_menu);
			
			unset($this->lang);
			
			} else {
				die(_CONTROLLER_ADMIN_NOT_FOUND);
			}
		}
	}
	
	public function logout() {
		if(isset($_COOKIE['admin_god_balero'])) {
			
			try {
				
				/**
				 * Delete cookie admin
				 */
				
				setcookie("admin_god_balero", "", time()-3600);
				//header("Location: index.php?app=admin");
				header("Location: ./admin");
				
			} catch (Exception $e) {
				
				/**
				 * forzar
				 */
				
				setcookie("admin_god_balero", "", time()-1);
				
			}
			
		}
	} // end logout
	
}
