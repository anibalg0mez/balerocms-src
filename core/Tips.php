<?php

/**
 *
 * Tip.php
 * (c) Jun 13, 2013 lastprophet 
 * @author Anibal Gomez (lastprophet)
 * Balero CMS Open Source
 * Proyecto %100 mexicano bajo la licencia GNU.
 * PHP P.O.O. (M.V.C.)
 * Contacto: anibalgomez@icloud.com
 *
**/

class Tips {
		
	public function __construct() {
		
	}
	
	public function white($message) {
		$tip = file_get_contents(LOCAL_DIR . "/themes/universe/core/Tips/white.html");
		$tip = str_replace("{message}", $message, $tip);
		return $tip;
	}

	public function blue($message) {
		$tip = file_get_contents(LOCAL_DIR . "/themes/universe/core/Tips/blue.html");
		$tip = str_replace("{message}", $message, $tip);
		return $tip;
	}

	public function green($message) {
		$tip = file_get_contents(LOCAL_DIR . "/themes/universe/core/Tips/green.html");
		$tip = str_replace("{message}", $message, $tip);
		return $tip;
	}

	public function red($message) {
		$tip = file_get_contents(LOCAL_DIR . "/themes/universe/core/Tips/red.html");
		$tip = str_replace("{message}", $message, $tip);
		return $tip;
	}

	public function violet($message) {
		$tip = file_get_contents(LOCAL_DIR . "/themes/universe/core/Tips/violet.html");
		$tip = str_replace("{message}", $message, $tip);
		return $tip;
	}

	public function gradient($message) {
		$tip = file_get_contents(LOCAL_DIR . "/themes/universe/core/Tips/gradient.html");
		$tip = str_replace("{message}", $message, $tip);
		return $tip;
	}
	
}
