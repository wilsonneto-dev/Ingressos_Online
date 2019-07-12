<?php

class StdAdminPage{
	
	private $html;
	
	public $title;
	public $sub_title;
	public $page;
	
	public $form;
	public $table;
	
	public $table_header;
	public $table_content;
	
	public $form_fields;
	public $submit_text;
		
	public $cadastrar;
	public $back_link;
	public $title_back;
	
	public $botoes_extras;

	public $html_content;

	public $form_action;
	public $form_button_text;

	public $cadastrar_parametro;

	public function StdAdminPage(){
		
		$this->cadastrar = true;
		$this->html 		= "";
		$this->sub_title 	= "";
		$this->title 	= "";
		$this->title_back = "";
		$this->table_header 		= "";
		$this->table_content		= "";
		$this->form		= false;
		$this->table		= false;
		$this->form_fields 		= array();
		$this->back_link = false;
		$this->title_back = "";
		$this->submit_text = "Salvar";
		$this->botoes_extras = array();		
		$this->html_content = "";
		$this->form_action = "";
		$this->form_button_text = "";
		$this->cadastrar_parametro = "";		
	}
	
	private function make_page(){
		$this->html .= $this->make_header( $this->back_link );
		if( $this->form ) 
			$this->html .= $this->make_form();
		if( $this->table ) 
			$this->html .= $this->make_table();
		if( $this->html_content <> '' ) 
			$this->html .= $this->make_html_content();

		$this->html .= $this->make_extra_buttons();
	}	
	
	public function render(){
		$this->make_page();
		echo $this->html;
	}
	
	public function add_field( $args ){
		$this->form_fields[] = $args;
	}
	
	private function make_header( $back = false ){
		$header = "";
		if($back == true){
			$header = sprintf('
				<section class="content-header">
                    <h1>
                        %s
                        <small>%s</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="/admin/?pg=%s"><i class="fa fa-angle-double-left"></i> %s</a></li>
                    </ol>
                </section>
				', $this->title, $this->sub_title, $this->page, $this->title_back
			);
		}else{
			$header = sprintf('
				<section class="content-header">
                    <h1>
                        %s
                        <small>%s</small>
                    </h1>
                </section>
				', $this->title, $this->sub_title
			);
		}
		return $header;
	}	
	
	private function make_table( $back = false ){
		$table = sprintf('
			<section class="content">
	            
	            <section style="text-align: right;">
	    			<a href="/admin/?pg=%sCadastrar%s" class="btn btn-primary" style="display: '.( ($this->cadastrar == false) ? 'none':'inline-block' ).'">
						Novo / Cadastrar
					</a>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<br /><br />
				</section>
		
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">

							  	<div class="box-body table-responsive">
                                    <table class="table table-bordered table-hover data-table">
                                        <thead>
                                            <tr>
                                            	%s
                                            	<th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            %s
                              			</tbody>
                              			<tfoot>
                                            <tr>
                                            	%s
                                            	<th></th>
                                            </tr>
                                        </tfoot>
                          			<table>
                      			<div>
				
						</div>
					</div>
				</div>	
			</section>
			', $this->page, ( ($this->cadastrar_parametro != "")?("&".$this->cadastrar_parametro):("") ), $this->table_header, $this->table_content, $this->table_header
			);
		return $table;
	}

	private function make_form(){
		$form = sprintf('
			<section class="content">
	
		       <div class="row">
                    <div class="col-xs-12">
                		
                			<div class="box box-primary">
                                <!-- form start -->
                                <form class="f_admin" name="f_post" method="post" enctype="multipart/form-data" '.( $this->form_action == "" ? "" : ('action="'.$this->form_action.'"') ).'>
                                    <div class="box-body">
                                        
                                        %s

                                    </div>

                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-primary">'.( $this->form_button_text == "" ? "Salvar" : $this->form_button_text ).'</button>
                                    </div>
                                </form>
                            </div><!-- /.box -->

					</div>
				</div>	

			</section>
			', 
			$this->make_fields(), $this->page 
		);
		return $form;
	}
	
	private function make_html_content( ){
		$form = sprintf('
			<section class="content">
	
		       <div class="row">
                    <div class="col-xs-12">
                		
                			<div class="box box-primary">
                                <!-- form start -->
                                <div class="box-body">
                                    
                                    %s

                                </div>
	                        </div><!-- /.box -->

					</div>
				</div>	

			</section>
			', 
			$this->html_content
		);
		return $form;
	}
	
	private function make_fields(){
		
		$fields = "";
		foreach( $this->form_fields as $field ){ 
			$fields .= StdFormFactory::field( $field ); 
		}
		unset($field);
		return $fields; 
	}
	
	private function make_extra_buttons(){
		
		$buttons = "";
		foreach( $this->botoes_extras as $btn ){ 
			$buttons .= "<a class=\"btn btn-primary\" href=\"$btn[url]\" target=\"$btn[target]\">$btn[legenda]</a>&nbsp;&nbsp;"; 
		}
		return $buttons; 
	}
		
}

class StdFormFactory{
	
	public static function field( $args ){
		
		$html_field = "";
		
		$label = isset( $args["label"] ) ? $args["label"] : "Legenda";
		$type = isset( $args["type"] ) ? $args["type"] : "text";
		$required = isset( $args["required"] ) ? $args["required"] : false;
		$autofocus = isset( $args["autofocus"] ) ? $args["autofocus"] : false;
		$value = isset( $args["value"] ) ? $args["value"] : "";
		$placeholder = isset( $args["placeholder"] ) ? $args["placeholder"] : "";
		$name = isset( $args["name"] ) ? $args["name"] : "campo";
		$html = isset( $args["html"] ) ? $args["html"] : "";
		$sql = isset( $args["sql"] ) ? $args["sql"] : "";
		$options = isset( $args["options"] ) ? $args["options"] : array();
		$checked = isset( $args["checked"] ) ? $args["checked"] : false;
		$icon = isset( $args["icon"] ) ? $args["icon"] : "";

		switch( $type ){

			case "location":
				$lat = "";
				$lng = "";
				if($value != ""){
					$arr = explode(";", $value);
					if(isset($arr[0])) $lat = $arr[0];
					if(isset($arr[1])) $lng = $arr[1];
				}
	 			$html_field .= ('
					<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
					<script type="text/javascript" src="/admin/js/location/control.js"></script>
					<label>' . $label . ':</label><br />
					<div id="mapa" style="width: 690px; height: 300px; border: 2px solid #61a5db;"></div>
					<input type="hidden" id="coord_x" name="'.$name.'_latitude" value="'.$lat.'" data-value="-20.806047438659185" />
					<input type="hidden" id="coord_y" name="'.$name.'_longitude" value="'.$lng.'" data-value="-49.38074469566345" />
				');
				break;

			case "pop-list":
				$r = new Repeater();
				$r->campos = "value;text";
				$r->sql = $sql;
				$r->txtVazio = "<h4>N&atilde;o h&aacute; itens cadastrados...</h4>";
				$r->txtItem = "
					<li class=\"item\" id=\"list-".$name."-li-#value\"><input type=\"checkbox\" value=\"#value\" class=\"chk\" />#text</li>
				";
				$r->exec();
				$html_field .= ('
					<label for="'.$name.'">'.$label.':</label>
					<input type="hidden" id="'.$name.'" name="'.$name.'" />
					<ul class="pop-list-selected" id="list-'.$name.'-selected"></ul>
					<img src="/adm/imgs/pop-list.png" class="pop-list-button" data-title="'.$name.'" data-list="#list-'.$name.'" />
					<br />
					<div class="pop-list-wrapper" id="list-'.$name.'">
						<center>
							<input type="text" class="pop-list-filter txt_m" />
						</center>
						<ul data-field="' . $name . '" data-selected="#list-'.$name.'-selected" id="list-pop-'.$name.'" data-input="#'.$name.'">
						' . $r->html . '
						</ul>
					</div>
					');
				if( $value != "" ){
					$html_field .= "<script>$(document).ready(function(){";
					foreach( explode(",", $value) as $val ){
						$html_field .= "pre_check('#list-".$name."-li-".$val."');";
					}
					$html_field .= "});</script>";
				}
				break;
			case "sql-select":
				//select sub-categoria
				$selCategoria = new SqlSelect( $sql ); 
				$selCategoria->nome = $name;
				if( $value != "" ) { $selCategoria->valorSelecionado = $value; }
				$selCategoria->exec(); 
				$html_field .= ('
					<label for="'.$name.'">'.$label.':</label>
					' . $selCategoria->html . '<br />
					');
				break;
			case "text":
			case "email":
			case "password":
			case "url":
			case "num":
				$html_field .= ('
					<div class="form-group">
	                    <label for="'.$name.'">'.$label.'</label>
	                    ' 
	                    . ( ($icon != "") ? ('
	                    <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa '.$icon.'"></i>
                            </div>
			            ') : "" )
			            .'   <input 
		                    	type="'.( ($type == 'data' || $type == 'num' ) ? 'text' : $type ). '" 
		                    	class="form-control' . ($required?' obrigatorio':'' ) .($type == "data"?' data':'' ) .($type == "num"?' num':'' ) . '" 
		                    	id="'.$name.'" 
		                    	'. ( ($placeholder != '') ? ('placeholder="'.$placeholder.'" ') : '' ) .'
								'. ( ($required != '') ? ('required="'.$required.'" ') : '' ) .'
								' . ($autofocus ?' autofocus ' : '' ) .' 
								name="'.$name.'"
								'. ( ($value != '') ? ('value="'.bd_limpa( $value ).'" ') : '' ).'
							/>
' 
	                    . ( ($icon != "") ? ('</div>') : "" )
			            .'
	                </div>');
				break;
			case "data":
				$html_field .= ('
						<div class="form-group">
	            	        <label for="'.$name.'">'.$label.'</label>
	                        <div class="input-group">
	                            <div class="input-group-addon">
	                                <i class="fa fa-calendar"></i>
	                            </div>
				                <input 
			                    	type="'.( ($type == 'data' || $type == 'num' ) ? 'text' : $type ). '" 
			                    	class="form-control pull-right' . ($required?' obrigatorio':'' ) .($type == "data"?' data':'' ) .($type == "num"?' num':'' ) . '" 
			                    	id="'.$name.'" 
			                    	'. ( ($placeholder != '') ? ('placeholder="'.$placeholder.'" ') : '' ) .'
									'. ( ($required != '') ? ('required="'.$required.'" ') : '' ) .'
									' . ($autofocus ?' autofocus ' : '' ) .' 
									name="'.$name.'"
									'. ( ($value != '') ? ('value="'.bd_limpa( $value ).'" ') : '' ).'
								/>
	                        </div><!-- /.input group -->
	                    </div><!-- /.form group -->
					');
				break;
			case "file":
				$html_field .= ('
					<div class="form-group">
	                    <label for="'.$name.'">'.$label.'</label>
	                    <input 
	                    	type="'.( ($type == 'data' || $type == 'num' ) ? 'text' : $type ). '" 
	                    	class="' . ($required?' obrigatorio':'' ) .($type == "data"?' data':'' ) .($type == "num"?' num':'' ) . '" 
	                    	'. ( ($required != '') ? ('required="'.$required.'" ') : '' ) .'
							id="'.$name.'" 
	                    	'. ( ($placeholder != '') ? ('placeholder="'.$placeholder.'" ') : '' ) .'
							' . ($autofocus ?' autofocus ' : '' ) .' 
							name="'.$name.'"
							'. ( ($value != '') ? ('value="'.bd_limpa( $value ).'" ') : '' ).'
						/>
	                </div>');
				break;
			case "image-view":
				if( $value != "" && $value != "/"  ){
					$html_field .= ('
					<label>' . $label . ':</label>
					<img style="vertical-align: top; max-width: 400px;max-height: 200px;" src="'.$value.'" />
					<br />
					');
				}
				break;
			case "textarea":
			case "textarea-m":
					$html_field .= ('
					<label>' . $label . ':</label> '. ( $type == "textarea" ? '<br />' : '' ) .
					'<textarea '.
						'id="'.$type.'" '.
						'class="form-control ' . ($required?'obrigatorio':'' ) . ($type == "textarea" ? ' txt_g txt_a' : ' txt_m txt_a_m' ) . '" '.
						'' . ($required ?' required ':'' ) . 
						''. ( ($placeholder != '') ? ('placeholder="'.$placeholder.'" ') : '' ) .
						'' . ($autofocus ?' autofocus ' : '' ) . 
						'name="'.$name.'">'.
						($value != "" ? $value : '').
					'</textarea><br />
					');
				break;
			case "select":
				$opts_html = "";
				foreach ($options as $k => $v) {
					$opts_html .= "<option value=\"$k\" ".(($v == $value) ? "selected=\"selected\"":"").">$v</option>";
				}
				$html_field .= ('
					<div class="form-group">
	                    <label for="'.$name.'">'.$label.'</label>
	                    <select class="form-control" id="'.$name.'" name="'.$name.'">'.$opts_html.'</select>
	                </div>');
				break;
			case "html-field":
				$html_field .= ('
					<label>' . $label . ':</label> '.
					'' . $html . '<br />' .
					'');
				break;
			case "checkbox":
				$html_field .= ('
						<div class="checkbox">
	                        <label>
	                            <input type="checkbox" value="ativo" name="'.$name.'" '.( ( $value != "0" ) ? " checked=\"checked\"" : "" ).'> ' . $label . '
	                        </label>
	                    </div>
				');
				break;			
			case "editor":
						$html_field .= ('
					<label>' . $label . ':</label> '.  '<br />' .
					'<textarea id="editor_html" name="'.$name.'" rows="10" cols="80">'.$value.'</textarea>
                    <br />
					');
				break;
			default:
				$html_field = "Erro ao gerar campo...";
		}
		
		
		return $html_field;
	}

}

		

?>