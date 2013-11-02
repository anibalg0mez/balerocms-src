<?php

/**
 *
 * index.php
 * (c) Feb 26, 2013 lastprophet 
 * @author Anibal Gomez (lastprophet)
 * Balero CMS Open Source
 * Proyecto %100 mexicano bajo la licencia GNU.
 * PHP P.O.O. (M.V.C.)
 * Contacto: anibalgomez@icloud.com
 * ============================================
 * Pretty URLs by default on version 0.3+
 *
**/

/**
 * Desarrolador / Developer (-1)
 * Usuario / User (0)
 * (Editar)
 */

error_reporting(0); // developer by default

/**
 * 
 * Para servidores con Windows.
 * (No editar)
 */

$dir = dirname(__FILE__);
$dir = str_replace("\\", "/", $dir);

/**
 *
 * LOCAL_DIR = Directorio dónde se encuentra nuestro sistema.
 * (No editar)
 */

define("LOCAL_DIR", $dir);

/**
 * 
 * Cargamos Balero CMS.
 * (No editar)
 */

require_once(LOCAL_DIR . "/core/Router.php");

/**
 * 
 * Hacemos magia.
 * (No editar)
 */

$objRouter = new Router();
$objRouter->init();
