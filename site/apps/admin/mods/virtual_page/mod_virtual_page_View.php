<?php

/**
 *
 * blog_view.php
 * (c) Jun 12, 2013 lastprophet 
 * @author Anibal Gomez (lastprophet)
 * Balero CMS Open Source
 * Proyecto %100 mexicano bajo la licencia GNU.
 * PHP P.O.O. (M.V.C.)
 * Contacto: anibalgomez@icloud.com
 *
**/

class mod_virtual_page_View extends mod_virtual_page_Model {
	
	public $mod_name = _VIRTUAL_PAGE;
	
	/**
	 * Variable de contenido $content
	 */
	
	public $content = "";
	
	public $editor_headers;
	
	/**
	 * 
	 * Recibir el menu de módulos desde el Router.
	 */
	
	public $menu;
	
	public function __construct() {
		
		/**
		 * 
		 * Obtener los registros totales de esta tabla para colocarlos en el titulo de modulo
		 * en el panel izquierdo del menu. Ejemplo Blog (70)
		 */
		
	
		$this->loadModelvars();
		
	}
	
	
	public function new_virtual_page_view() {

		/**
		 * 
		 * @$this->mod_name Nombre de el módulo (Aparecera en el header del contededor)
		 **/

		$this->mod_name = _NEW_VIRTUAL_PAGE;
		
		$editor = new Form("index.php?app=admin&mod_controller=virtual_page&sr=new_page");
		$editor->Label(_CONFIG_PAGE);
		$editor->RadioButton(_ENABLED_CONTENT, "1", "a", 1);
		$editor->RadioButton(_DISABLED_CONTENT, "0", "a", 0);
		$editor->Label(_VIRTUAL_PAGE_CONTENT);
		$editor->TextField(_VIRTUAL_PAGE_TITLE, "virtual_title", "");
		$editor->Editor($this->editor);
		$editor->SubmitButton(_VIRTUAL_PAGE_CREATE);
		
		$this->content .= $editor->Show();
		
		$tip = new Tips();
		$tip_type2 = $tip->blue(_VIRTUAL_PAGE_TIP_PREVIEW);
		$tip_type2_2 = $tip->green(_MARKDOWN_REFERENCE);
		$this->content .= $tip_type2;
		$this->content .= $tip_type2_2;
		
	}
	
	
	public function edit_virtual_page_view($id) {
	
		$db = new mod_virtual_page_Model();
		
		/**
		 * @$this->mod_name Nombre de el módulo (Aparecera en el header del contededor)
		 **/
	
		//echo "bd" . $db->return_value($id);
		
		$this->mod_name = _EDIT_VIRTUAL_PAGE;
	
		$editor = new Form("index.php?app=admin&mod_controller=virtual_page&sr=edit_page&id=$id");
		$editor->Label(_CONFIG_VIRTUAL_PAGE);
		if($db->return_value($id) == 1) {
			$editor->RadioButton(_ENABLED_CONTENT, "1", "a", 1);
			$editor->RadioButton(_DISABLED_CONTENT, "0", "a", 0);
		} else {
			$editor->RadioButton(_ENABLED_CONTENT, "1", "a", 0);
			$editor->RadioButton(_DISABLED_CONTENT, "0", "a", 1);
		}
		$editor->HiddenField("id", $id);
		$editor->Label(_VIRTUAL_PAGE_CONTENT);
		$editor->TextField(_VIRTUAL_PAGE_TITLE, "virtual_title", $db->return_virtual_title($id));
		$editor->Editor($this->editor);
		$editor->HiddenField("import", $db->return_virtual_content($id));
		$editor->SubmitButton(_VIRTUAL_PAGE_EDIT);
	
		$this->content .= $editor->Show();
	
		$tip = new Tips();
		$tip_type2 = $tip->blue(_VIRTUAL_PAGE_TIP_PREVIEW);
		$tip_type2_2 = $tip->green(_MARKDOWN_REFERENCE);
		$this->content .= $tip_type2;
		$this->content .= $tip_type2_2;
	
	}
	
	
	public function site_map_view() {
		
		$s = new mod_virtual_page_Model();
		$s->site_map_model();
		
		$this->content .= _TREE_VIRTUAL_PAGE;
		
		/**
		 * Como renderizar página utilizando ThemeLoader
		 * Para documentación de desarrollador
		 */
		
		// tramos configuracion (variables)
		$cfg = new configSettings();
		$cfg->LoadSettings();
		
		// nueva variable de tipo objeto
		$objTemplate = new ThemeLoader(LOCAL_DIR . "/site/apps/admin/mods/virtual_page/html/tree.html");

		// variable para acumular el contenido de los loops (html)
		$html = "";
		
		try {
			if(empty($s->rows)) {
				$html = _NO_VIRTUAL_PAGES;
				throw new Exception();
			} 
		// recorremos los datos o la query y lo almacenamos en $html
		foreach ($s->rows as $row) {
			// obtener el contenido de loop.html y reemplazarlo con su contenido
			$tmp = file_get_contents(LOCAL_DIR . "/site/apps/admin/mods/virtual_page/html/loop.html");
			$tmp = str_replace("{virtual_title}", $row['virtual_title'], $tmp);
			$tmp = str_replace("{id}", $row['id'], $tmp);
			
			// almacenando página dentro del loop (html)
			$html = $html . $tmp;
		
			
		}
		} catch (Exception $e) {
			
		}
		
		// creamos diccionario y reemplazamos $html
		$array = array(
				'site'=>$cfg->title,
				'loop'=>$html
		);
	
		// metemos el contenido del template en el contenido de la pagina
		$this->content .= $objTemplate->renderPage($array);
		
		
		
		/******************************************************/
		
		$msgbox = new MsgBox(_VIRTUAL_PAGE_NOTE, _VIRTUAL_PAGE_NOTE_MESSAGE);
		$this->content .= $msgbox->Show();
		
		// renderizamos finalmente todo el documento
		$this->Render();
		
	}
	

	public function delete_post_confirm_view() {
		
		if(isset($_POST['cancel'])) {
			header("Location: index.php?app=admin&mod_controller=virtual_page&sr=site_map");
			die();
		}
		
		$id = new Security();
		$id = $id->shield($_GET['id']);
		
		$frm = new Form("index.php?app=admin&mod_controller=virtual_page&sr=delete_page_confirm");
		$frm->HiddenField("id", $id);
		$frm->SubmitButton(_VIRTUAL_PAGE_CONFIRM, "submit");
		$frm->SubmitButton(_VIRTUAL_PAGE_CANCEL, "cancel");
		
		$msg = new Tips();
		$this->content .= $msg->red(_VIRTUAL_PAGE_CONFIRM_MESSAGE);
		$this->content .= $frm->Show();
		
	}
	
	public function sucessMessage($message) {
		$v_message = new Tips();
		$string_var_message = $v_message->green($message);
		
		/**
		 * 
		 * @$string_var_message se necesita almacenar el contenido en una
		 * variable de tipo String.
		 */
		
		$this->content .= $string_var_message;
		
	}
	
	public function errorMessage($message) {
		$v_message = new Tips();
		$string_var_message = $v_message->red($message);
	
		/**
		 *
		 * @$string_var_message se necesita almacenar el contenido en una
		 * variable de tipo String.
		 */
		
		
		 $this->content .= $string_var_message;
	
	}
	
	
	/**
	 * @truncate_word() Cortar palabras o limitar.
	 * @param String $string cadena
	 * @param INT $limit limite
	 */
	
	public function truncate_word($string, $limit) {
		
		$truncate_string = substr($string, 0, $limit);
		
		$new_string = htmlspecialchars($truncate_string) . "...";
		
		return $new_string;
		
	}
		
	
	public function Render() {
		
		/**
		 * 
		 * Diccionario (variables de la plantilla).
		 */
		
		$array = array(
				'editor_headers'=>$this->editor_headers,
				'content'=>$this->content,
				'mod_name'=>$this->mod_name,
				'mod_menu'=>$this->menu
				);
		
		/**
		 * 
		 * Renderizamos nuestra página.
		 */

		$objTheme = new ThemeLoader(LOCAL_DIR . "/site/apps/admin/themes/default/panel.html");		
		echo $objTheme->renderPage($array);
		
	
	}

	
}
