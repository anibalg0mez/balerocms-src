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
	
	public $basepath;
	
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
		$editor->TextArea(_VIRTUAL_PAGE_CONTENT, "content", "");
		$editor->SubmitButton(_VIRTUAL_PAGE_CREATE);
		
		/**
		 * Build html tab block
		*/
		
		$htmltab = "";
		$tab = new ThemeLoader(LOCAL_DIR . "/site/apps/admin/panel/tabs/UI.html");
		
		$array = array(
				'code' => "*",
				'content' => $editor->Show()
		);
		
		$htmltab .= $tab->renderPage($array);
		
		$this->content .= "<div class=\"set set-1\">";
		$this->content .= $htmltab;
		$this->content .= "</div>";
			
		$js = new ThemeLoader(LOCAL_DIR . "/site/apps/admin/panel/tabs/js.html");
		$this->content .= $js->renderPage(array());
		
		$tip = new MsgBox(_VIRTUAL_PAGE_TIP_PREVIEW, _MARKDOWN_REFERENCE);
		$tip_type2 = $tip->Show();
		
		$this->content .= $tip_type2;
		
		
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
		$editor->TextArea(_VIRTUAL_PAGE_CONTENT, "content", $db->return_virtual_content($id));
		$editor->HiddenField("import", $db->return_virtual_content($id));
		$editor->SubmitButton(_VIRTUAL_PAGE_EDIT);
	
		/**
		 * Build html tab block
		*/
		
		$htmltab = "";
		$tab = new ThemeLoader(LOCAL_DIR . "/site/apps/admin/panel/tabs/UI.html");
		
		$array = array(
				'code' => "*",
				'content' => $editor->Show()
		);
		
		$htmltab .= $tab->renderPage($array);
		

		/**
		 * Build lang tabs
		 */
		
		$settings = new configSettings();
		$settings->LoadSettings();
		
		if($settings->multilang == "yes") {
		
			$model = new mod_virtual_page_Model();
		
			$tab = new ThemeLoader(LOCAL_DIR . "/site/apps/admin/panel/tabs/UI.html");
		
			$objLangs = new mod_languages_Model();
			$langs = $objLangs->get_lenguages();
		
			$i = 0;
			foreach ($langs as $row) {
		
				$i++;
				//$model->return_post_title_multilang($_GET['id'], $row['code']);
				//$model->return_post_content_multilang($_GET['id'], $row['code']);

				$editor = new Form("index.php?app=admin&mod_controller=virtual_page&sr=page_multilang&id=$id");
				$editor->Label($row['label']);
				if($db->return_value($id) == 1) {
					$editor->RadioButton(_ENABLED_CONTENT, "1", "a", 1);
					$editor->RadioButton(_DISABLED_CONTENT, "0", "a", 0);
				} else {
					$editor->RadioButton(_ENABLED_CONTENT, "1", "a", 0);
					$editor->RadioButton(_DISABLED_CONTENT, "0", "a", 1);
				}
				$editor->HiddenField("id", $id);
				$editor->Label(_VIRTUAL_PAGE_CONTENT);
				$model->return_virtual_title_multilang($id, $row['code']);
				$editor->TextField(_VIRTUAL_PAGE_TITLE, "virtual_title", $model->virtual_title);
				$model->return_virtual_content_multilang($id, $row['code']);
				$editor->TextArea(_VIRTUAL_PAGE_CONTENT, "virtual_content", $model->virtual_content);
				$editor->HiddenField("import", $model->virtual_content);
				$editor->HiddenField("code", $row['code']);
				$editor->SubmitButton(_VIRTUAL_PAGE_EDIT);
				
				$array = array(
						'code' => $row['code'],
						'content' => $editor->Show()
				);
					
				/**
				 * Build html tab block
				*/
		
				$htmltab .= $tab->renderPage($array);
		
				
			} // for each
				
		} // end if
		
		$this->content .= "<div class=\"set set-1\">";
		$this->content .= $htmltab;
		$this->content .= "</div>";
		
		$js = new ThemeLoader(LOCAL_DIR . "/site/apps/admin/panel/tabs/js.html");
		$this->content .= $js->renderPage(array());
	
		$tip = new MsgBox("", _MARKDOWN_REFERENCE);
		$tip_type2 = $tip->Show();
		$this->content .= $tip_type2;
	
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
		$this->basepath = $cfg->basepath;
		
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
		
		$msg = new MsgBox(_VIRTUAL_PAGE_CONFIRM_MESSAGE, $frm->Show());
		$this->content .= $msg->Show();
		
	}
	
	public function sucessMessage($message) {
		$v_message = new MsgBox("", $message);
		$string_var_message = $v_message->Show();
		
		/**
		 * 
		 * @$string_var_message se necesita almacenar el contenido en una
		 * variable de tipo String.
		 */
		
		$this->content .= $string_var_message;
		
	}
	
	public function errorMessage($message) {
		$v_message = new MsgBox("", $message);
		$string_var_message = $v_message->Show();
	
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
				'basepath'=>$this->basepath,
				'content'=>$this->content,
				'mod_name'=>$this->mod_name,
				'mod_menu'=>$this->menu
				);
		
		/**
		 * 
		 * Renderizamos nuestra página.
		 */

		$objTheme = new ThemeLoader(LOCAL_DIR . "/site/apps/admin/panel/html/panel.html");		
		echo $objTheme->renderPage($array);
		
	
	}

	
}
