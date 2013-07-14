<?php

/**
 *
 * blog_controller.php
 * (c) Jun 11, 2013 lastprophet 
 * @author Anibal Gomez (lastprophet)
 * Balero CMS Open Source
 * Proyecto %100 mexicano bajo la licencia GNU.
 * PHP P.O.O. (M.V.C.)
 * Contacto: anibalgomez@icloud.com
 *
**/

class mod_virtual_page_Controller {
	
	public $modModel;
	public $modView;
	
	public function __construct($menu) {
		
		
		// cargar vista de módulo.
		try {
			$this->modModel = new mod_virtual_page_Model();
			$this->modView = new mod_virtual_page_View();
			$this->modView->menu = $menu;
			
		} catch (Exception $e) {
			die("error" . $e->getMessage());
		}
		
		// Automatizar el controlador
		try {
		$handler = new ModControllerHandler($this);
		} catch (Exception $e) {
			die($e->getMessage());
		}
		
// 		switch ($_GET['sr']) {
// 			case "prueba":
// 				echo "funciona";
// 				break;
// 		}
		
	}
	
	
	public function main() {

		/**
		 * No usamos main() en modulos de admin, sin embargo
		 * Necesitamos declararlo por si alguien intenta acceder a el
		 * simplemente lo redireccioonamos.
		 */
		
		header("Location: index.php?app=admin");
		
	}
	
	// controlador nueva pagina virtual
	public function new_page() {
		
		
		
		if(isset($_POST['submit'])) {
			
			/**
			 *
			 * @$plain_text Obtener el texto plano de el contenido que nos pasa el usuario.
			 */
			
			$plain_text = "";
			
			
			/**
			 * 
			 * @function htmlentities() no es compatible con acentos.
			 */
			
			//$plain_text = htmlspecialchars($_POST['content']);
			$plain_text = $_POST['content'];
			
			/**
			 * 
			 * Llamar la clase Markdown.
			 */
			
			//$render_html = Markdown::defaultTransform($plain_text);
			
			//$this->modView->content .= $plain_text;
			//$this->modView->content .= "----------------------";
			//$this->modView->content .= $render_html;
			
			
			
			try {
				if(empty($_POST['content'])) {
					$this->modView->errorMessage(_PAGE_POST_ERROR);
				}elseif(empty($_POST['virtual_title'])) {
					$this->modView->errorMessage(_PAGE_POST_EMPTY_TITLE);
				} else {
					$this->modModel->add_page_model($_POST['virtual_title'], $plain_text, $_POST['a']);
					$this->modView->sucessMessage(_ADDED_SUCESSFULLY);
				}
			} catch (Exception $e) {
				$this->modView->errorMessage(_ADDING_POST_ERROR . $e->getMessage());
			}
			
		}
		
		try {
			$this->modView->new_virtual_page_view();
			$this->modView->Render();
		} catch(Exception $e) {
			
		}
		
	}
	
	public function site_map() {
		
		try {
			
			$this->modModel->site_map_model();
			$this->modView->site_map_view($this->modModel->rows);
			
		} catch (Exceptipn $e) {
			
		}
		
	}
	
	public function edit_page() {
		
		
		try {
			
			if(empty($_GET['id'])) {
				throw new Exception(_NO_RESULTS);
			}
				
			if(!isset($_GET['id'])) {
				throw new Exception(_ID_DONT_EXIST);
			}
			
			$id = new Security();
			$_id = $id->shield($_GET['id']);
			
			if(isset($_POST['submit'])) {
				
				if(empty($_POST['content'])) {
					throw new Exception(_PAGE_POST_ERROR);
				}
				if(empty($_POST['virtual_title'])) {
					throw new Exception(_PAGE_POST_EMPTY_TITLE);
				}
				
				$this->modModel->update_virtual_content($_POST['id'], $_POST['virtual_title'], $_POST['content'], $_POST['a']);
				
				$this->modView->sucessMessage(_SAVING_CONTENT_OK);
			}
			$this->modView->edit_virtual_page_view($_id);
			$this->modView->Render();
		} catch (Exception $e) {
			$this->modView->errorMessage(_ADDING_PAGE_ERROR . " " . $e->getMessage());
			$this->modModel->site_map_model();
			$this->modView->edit_virtual_page_view($id);
			$this->modView->Render();	
		}
		
	}
	
	
	public function delete_page_confirm() {
		
		try {
			if(isset($_POST['submit'])) {
				$this->modModel->delete_page_confirm_model($_POST['id']);
				$this->modView->sucessMessage(_CONTENT_DELETED_OK);
				$this->modView->site_map_view($this->modModel->rows);
			} else {
				$this->modView->delete_post_confirm_view();
				$this->modView->Render();
			}
		} catch (Exception $e) {
			$this->modView->errorMessage($e->getMessage());
			$this->modView->site_map_view($this->modModel->rows);
		}
		
	}

	
	
}
