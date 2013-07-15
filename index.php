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
 *
**/

/**
 * Modo desarrolador (-1)
 * Modo usuario (0)
 */

error_reporting(0);

/**
 * 
 * Para servidores con Windows.
 */

$dir = dirname(__FILE__);
$dir = str_replace("\\", "/", $dir);

/**
 *
 * LOCAL_DIR = Directorio dónde se encuentra nuestro sistema.
 */

define("LOCAL_DIR", $dir);

/**
 * 
 * Cargamos Balero CMS.
 */

require_once(LOCAL_DIR . "/core/Router.php");

/**
 * 
 * Hacemos magia.
 */

$objRouter = new Router();
$objRouter->init();
