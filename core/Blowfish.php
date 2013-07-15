<?php

/**
 *
 * Blowfish.php
 * (c) May 3, 2013 lastprophet 
 * @author Anibal Gomez (lastprophet)
 * Balero CMS Open Source
 * Proyecto %100 mexicano bajo la licencia GNU.
 * PHP P.O.O. (M.V.C.)
 * Contacto: anibalgomez@icloud.com
 *
**/


/**
 * 
 * @author Anibal Gomez - lastprophet
 * Encriptar passwords en BlowFish con 'salt'.
 *
 */

/**
 *
 * Balero CMS implementa el sistema llamado BlowFish Login :) :) :)
 * @author lastprophet
 *
 */

class Blowfish {
	
	private $pwd;
	private $pwd_string;
	
	public $message;

// referencias en esta pagina
//http://www.the-art-of-web.com/php/blowfish-crypt/#.UbTIRBx38Yw
// verificar                  //el pwd encriptado
// 	if(crypt("texto_plano", $pwd_hashed) == $pwd_hashed) {
// 		echo "pwd correcto";
// 	} else {
// 		echo "pwd incorrecto";
// 	}
	
	public function genpwd($pwd = "") {
		
		/**
		 * 
		 * generar salt
		 */
		
		$salt = ""; 
		$salt_chars = array_merge(range('A','Z'), range('a','z'), range(0,9)); 
		
		for($i=0; $i < 22; $i++) {
			$salt .= $salt_chars[array_rand($salt_chars)];
		} 
		
		return crypt($pwd, sprintf('$2a$%02d$', 7) . $salt);
		
	}	
	
	public function verify_hash($text, $hash) {
		
		if(crypt($text, $hash) == $hash) {
			return TRUE;
		} else {
			return FALSE;
		}
		
	}
	
	public function login_form($view) {

		if(isset($view)) {
			$login = file_get_contents($view);
			$login = str_replace("{message}", $this->message, $login);
		}
		
		return $login;
		
	}
	
	/**
	 * Metodos magicos de PHP
	 */
	
	public function __destruct() {
		$this->pwd;
	}

	
	
}