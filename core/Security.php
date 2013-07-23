<?php

/**
 *
 * Security.php
 * (c) Apr 5, 2013 lastprophet 
 * @author Anibal Gomez (lastprophet)
 * Balero CMS Open Source
 * Proyecto %100 mexicano bajo la licencia GNU.
 * PHP P.O.O. (M.V.C.)
 * Contacto: anibalgomez@icloud.com
 *
**/

class Security {
	
	private $var;

	
	/**
	 * 
	 * We need HTML tags like '<' or '>'
	 * but not the javascript tags
	 * 
	 */
	
	public function noJS($var) {
		$script_str = $var;
		$script_str = str_replace("'", "", $script_str);
		//return preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $var);
		$script_str = htmlspecialchars_decode($script_str);
		$search_arr = array('<script', '</script>');
		$script_str = str_ireplace($search_arr, $search_arr, $script_str);
		$split_arr = explode('<script', $script_str);
		$remove_jscode_arr = array();
		foreach($split_arr as $key => $val) {
			$newarr = explode('</script>', $split_arr[$key]);
			$remove_jscode_arr[] = ($key == 0) ? $newarr[0] : $newarr[1];
		}
		
		return implode('', $remove_jscode_arr);
		
	}
	
	/**
	 * Blindar variable
	**/
	
	public function shield($var = "") {

		$this->var = $var;
		
		/**
		 * Nivel de protección.
		 */
		
		$this->Level1($this->var);
		$this->Level2($this->var);
		$this->Level3($this->var);

		return $this->fix();
		
	}

	public function fix() {
		return $this->__toString();
	}
	
	public function Level1($str) {
		$this->var = htmlentities($str);
		return $this->var;
	}
	
	public function Level2($str) {
		
		/**
		 * 
		 * Remover caracteres potencialmente peligrosos
		 */
		
		$this->var = str_replace("'", "", $str);
		$this->var = str_replace(".", "", $str);
		$this->var = str_replace("..", "", $str);
		$this->var = str_replace("/", "", $str);
		$this->var = str_replace("%20", "", $str);
		$this->var = str_replace("__", "", $str);
		return $this->var;
	}
	
	public function Level3($str) {
		$this->var = str_replace("document.cookie", "", $str);
		$this->var = str_replace("alert(*)", "", $str);
		return $this->var;
	}
	
	public function __toString() {
		return (string)$this->var;
	}
	
	public function __destruct() {
		unset ($this->var);
	}
	
}
