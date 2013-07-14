<?php

/**
 *
 * Language.php
 * (c) Jul 7, 2013 lastprophet 
 * @author Anibal Gomez (lastprophet)
 * Balero CMS Open Source
 * Proyecto %100 mexicano bajo la licencia GNU.
 * PHP P.O.O. (M.V.C.)
 * Contacto: anibalgomez@icloud.com
 *
**/

class Language {
	
	public $lang;
	
	public function __construct() {
		$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
		$this->lang = $lang;
		//echo "lang: " . $lang;
	}
	
	/**
	 * init() core lang
	 */
	
	public function init() {
		
		$path = LOCAL_DIR . "/lang/";
		
		switch ($this->lang) {
			case $this->lang:
			$this->include_lang($path . $this->lang . ".php");
			break;
				
			default:
			$this->include_lang($path . "en" . ".php");
		}

	}
	
	/**
	 * 
	 * for init() method 
	 */
	
	public function include_lang($path) {
		if(file_exists($path)) {
			include_once($path);
		}
	}
	
	/**
	 * init() modules lang
	 * @param String $mod
	 */
	
	public function init_mods_lang($mod) {
		$path = LOCAL_DIR . "/site/apps/admin/mods/" . $mod . "/lang/";
		
		switch ($this->lang) {
			case $this->lang;
			$this->include_lang($path . $this->lang . ".php");
			break;
			
			default:
			$this->include_lang($path . "en" . ".php");
		}
		
	}
	
	/**
	 * init() apps lang
	 * @param String $app
	 */
	
	public function init_apps_lang($app) {
		$path = LOCAL_DIR . "/site/apps/" . $app . "/lang/";
	
		switch ($this->lang) {
			case $this->lang;
			$this->include_lang($path . $this->lang . ".php");
			break;
				
			default:
				$this->include_lang($path . "en" . ".php");
		}
	
	}
	
}