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

class mod_blog_View extends configSettings {
	
	public $mod_name = "Comenzar";
	
	/**
	 * Variable de contenido $content
	 */
	
	public $content = "";
	
	/**
	 * 
	 * Recibir el menu de módulos desde el Router.
	 */
	
	public $menu;
	
	public $min;
	
	public function __construct() {
		
		/**
		 * 
		 * Obtener los registros totales de esta tabla para colocarlos en el titulo de modulo
		 * en el panel izquierdo del menu. Ejemplo Blog (70)
		 */
		
	$this->LoadSettings();
		
		
	}
	
	
	public function new_post_view() {

		/**
		 * @$this->mod_name Nombre de el módulo (Aparecera en el header del contededor)
		 **/
		
		$this->mod_name = _ADD_NEW_POST;
		
		/**
		 * Build default lang tab
		 */
		
		$htmltab = "";
		$tab = new ThemeLoader(LOCAL_DIR . "/site/apps/admin/panel/tabs/UI.html");
		
		// default or non-multilanguage form
		$frmDefault = new Form("index.php?app=admin&mod_controller=blog&sr=new_post");
		$frmDefault->Label(_LANG_DEFAULT);
		$frmDefault->Label(_NEW_POST);
		$frmDefault->TextField(_POST_TITLE, "title", "");
		$frmDefault->TextArea(_POST_MESSAGE, "content", "");
		$frmDefault->SubmitButton(_OK_MESSAGE);
		
		$array = array(
				'code' => "*",
				'content' => $frmDefault->Show()
		);
		
		$htmltab .= $tab->renderPage($array);
		
		
		
		/**
		 * Build html tab block
		 */
			
		$this->content .= "<div class=\"set set-1\">";
		$this->content .= $htmltab;
		$this->content .= "</div>";
			
		$js = new ThemeLoader(LOCAL_DIR . "/site/apps/admin/panel/tabs/js.html");
		$this->content .= $js->renderPage(array());
		
		$tip = new Tips();
		//$tip_type2 = $tip->blue(_EDITOR_PREVIEW_MESSAGE_TIP);
		$tip_type2_2 = $tip->green(_MARKDOWN_REFERENCE);
		//$this->content .= $tip_type2;
		$this->content .= $tip_type2_2;
		
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
	
	public function print_post($rows) {
		
		
		/**
		 * Pasar parametros al renderizador de filas.
		 */
		
		// leemos el parametro, en este caso los datos
	
		//				recorrer datos almacenados en $rows[]
		//				lo hacemos desde la vista:
		
		$html = "";
		$color = "color1";
		$i = $this->min;
		
		foreach ($rows as $row) {
			$i++;
			if($color == "color1") {
				$color = "color2";
			}  else {
				$color = "color1";
			}
	
			// Guardar filas renderizadas en $html
			$html .= $this->RenderTableRows($i, $row['id'], $row['title'], $row['message'], $color);
			
		}
		
		$this->content .= $this->RenderTable($html);
	
	}
	
	public function edit_view($id) {
		
			
		$this->mod_name = _EDIT_POST;

		/**
		 * Build default lang tab
		 */
		
		$htmltab = "";
		$tab = new ThemeLoader(LOCAL_DIR . "/site/apps/admin/panel/tabs/UI.html");
		
		
		$editor = new Form("index.php?app=admin&mod_controller=blog&sr=edit_post&id=$id");
			
		// traer datos de modelo
		$model_edit = new mod_blog_Model();
		// regresa el titulo del post unicamente por ID
		$title = $model_edit->return_post_title($id);
		// importar contenido en el editor , todo lo que este dentro de un campo llamado "import" por ID
		$import = $model_edit->return_post_content($id);
		
		$editor->Label(_EDIT_POST_CONTENT);
		$editor->TextField(_POST_TITLE, "title", $title);
		$editor->TextArea(_POST_MESSAGE, "content", $import);
		$editor->HiddenField("import", $import);
		$editor->SubmitButton(_OK_MESSAGE);
		
		$array = array(
				'code' => "*",
				'content' => $editor->Show()
		);
		
		$htmltab .= $tab->renderPage($array);
		
		/**
		 * Build lang tabs
		 */
		
		if($this->multilang == "yes") {
				
			$model = new mod_blog_Model();
				
			$tab = new ThemeLoader(LOCAL_DIR . "/site/apps/admin/panel/tabs/UI.html");
		
			$objLangs = new mod_languages_Model();
			$langs = $objLangs->get_lenguages();
		
			$i = 0;
			foreach ($langs as $row) {

				
				
				$i++;
				$model->return_post_title_multilang($_GET['id'], $row['code']);
				$model->return_post_content_multilang($_GET['id'], $row['code']);
				
				$editor = new Form("index.php?app=admin&mod_controller=blog&sr=post_multilang&id=$id");
				$editor->Label($row['label'] . " " . _VERSION);
				$editor->TextField(_POST_TITLE, "title", $model->title);
				$editor->TextArea(_POST_MESSAGE, "content", $model->message);
				$editor->HiddenField("import", $import);
				$editor->HiddenField("code", $row['code']);
				$editor->SubmitButton(_OK_MESSAGE);
		
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
		
		/**
		 * Build html tab block
		*/
			
		$this->content .= "<div class=\"set set-1\">";
		$this->content .= $htmltab;
		$this->content .= "</div>";
			
		$js = new ThemeLoader(LOCAL_DIR . "/site/apps/admin/panel/tabs/js.html");
		$this->content .= $js->renderPage(array());
		
		$this->sucessMessage(_BLOG_POST_MESSAGE);
			
		$this->Render();
		
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
	
	/**
	 *
	 * Renderizar filas de la tabla
	 * Primero renderizamos las filas y posteriormente la tabla con  los datos ya cargados.
	 * 
	 */
	
	public function RenderTableRows($i, $id, $title, $message, $color) {
		/**
		 * 
		 * Usamos el método truncate_word() para limitar palabras.
		 */
		
		
		$title = $this->truncate_word($title, 10);
		$message = $this->truncate_word($message, 25);
		
		/**
		 * 
		 * @Renderizamos las filas y las pasamos a la plantilla de la tabla
		 */
		
		$html_array = array(
				'i'=>$i,
				'id'=>$id,
				'title'=>$title,
				'message'=>$message,
				'color'=>$color
				);
		
		$html_theme = new ThemeLoader(LOCAL_DIR . "/site/apps/admin/mods/blog/html/td_loop.html");
		
		/**
		 * 
		 * @$html Cargar variable con las funciones anteriores para pasarla al sig. diccionario.
		 */
		
		$html = $html_theme->renderPage($html_array);
		
		
	return $html;
	
	
	}
	
	
	public function RenderTable($html) {
		
		/**
		 *
		 * Diccionario (variables de la plantilla).
		 */
		
		$array = array(
				'loop'=>$html
		);
		
		/**
		 *
		 * Renderizamos nuestra tabla finalmente
		 */
		
		$objTheme = new ThemeLoader(LOCAL_DIR . "/site/apps/admin/mods/blog/html/edit_delete_post.html");
		return $objTheme->renderPage($array);
		
	}
	
	
	public function Render() {
		
		$cfg = new configSettings();
		
		/**
		 * 
		 * Diccionario (variables de la plantilla).
		 */
		
		$array = array(
				'content'=>$this->content,
				'mod_name'=>$this->mod_name,
				'mod_menu'=>$this->menu,
				'basepath'=>$cfg->basepath
				);
		
		/**
		 * 
		 * Renderizamos nuestra página.
		 */

		$objTheme = new ThemeLoader(LOCAL_DIR . "/site/apps/admin/panel/html/panel.html");		
		echo $objTheme->renderPage($array);
		
	
	}

	
}
