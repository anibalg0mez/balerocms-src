<?php

/**
 *
 * Adminelements.php
 * (c) Jun 15, 2013 lastprophet 
 * @author Anibal Gomez (lastprophet)
 * Balero CMS Open Source
 * Proyecto %100 mexicano bajo la licencia GNU.
 * PHP P.O.O. (M.V.C.)
 * Contacto: anibalgomez@icloud.com
 *
**/

class AdminElements {
	
	public $mod_menu;
	public $html;
	
	public $mod_regs;
	
	public function __construct() {
		
	}
	
	public function mods_menu() {
		
		/**
		 * Cargar menu de modulos
		 */
		
		$array = array();
		$i = 0;
		$string_acum = "";
		
		if ($handle = opendir(LOCAL_DIR . "/site/apps/admin/mods/")) {
		
			// Código para debugear debugear
			//echo "Directory handle: $handle\n";
			//echo "Entries:\n";
		
			/* This is the correct way to loop over the directory. */
			while (false !== ($entry = readdir($handle))) {
				
				$i++;
			
				// DEBUG
				//echo "$entry\n<br>"; // Debug
				if(file_exists(LOCAL_DIR . "/site/apps/admin/mods/". $entry ."/html/mod_menu.html")) {
					
					$this->mod_menu = file_get_contents(LOCAL_DIR . "/site/apps/admin/mods/". $entry ."/html/mod_menu.html");
					
					require_once(LOCAL_DIR . "/site/apps/admin/mods/" . $entry . "/mod_".$entry."_Model.php");
					$model = "mod_" . $entry . "_Model";
					$regs[$i] = new $model();
					//echo $regs[$i]->get_regs();
					
					$class_methods = get_class_methods($model);
					
					foreach ($class_methods as $method_name) {
						//echo "$method_name\n";
						
						if($method_name == "get_regs") {
							$this->mod_menu = str_replace("{regs}", $regs[$i]->get_regs(), $this->mod_menu);
						}
						
					}
					
					//echo $this->mod_menu;
					
					$string_acum = $string_acum . $this->mod_menu;
					
				}
			}
		
		
			closedir($handle);
		}
		
		/**
		 * Fin cargar menu de modulos
		 */
		
		//echo $this->mod_menu;
		
		//return $this->mod_menu;
		return $string_acum;
		
		
	}
	
	
}
