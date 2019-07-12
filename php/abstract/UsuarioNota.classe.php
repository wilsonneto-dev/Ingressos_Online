<?php

class UsuarioNota{

	public $id;
	public $titulo;
	public $tipo;
	public $texto;
	public $data;
	public $cod_usuario;
	public $cod_admin;
	private $bd;
	
	public function UsuarioNota(){
		$this->id = '';
		$this->tipo = '';
		$this->titulo = "";
		$this->texto = "";
		$this->data = "";
		$this->cod_usuario = "";
		$this->cod_admin = "";
		$this->bd = new UsuarioNotaDAO();
	}
	
	public function cadastrar(){
		return $this->bd->cadastrar($this);
	}

	public function get(){
		return $this->bd->get($this);
	}

	public function atualizar(){
		return $this->bd->atualizar($this);
	}

	public function deletar(){
		return $this->bd->deletar($this);
	}

	// estáticos 
	public static function _get( $p_id ){
		$obj = new UsuarioNota();
		$obj->id = $p_id;
		if ( $obj->get() ) 
			return $obj;
		else 
			return null;
	}

	public static function _deletar( $p_id ){
		$obj = new UsuarioNota();
		$obj->id = $p_id;
		return ( $obj->deletar() ); 
	}

}

class UsuarioNotaDAO extends BaseDAO{
	
	public function cadastrar(UsuarioNota $p){
		try {
			if($this->abreConexao()){
				$str_q = "
					INSERT INTO 
					usuario_nota( 
						titulo, 
						tipo, 
						texto, 
						data, 
						cod_usuario,
						cod_admin
					) VALUES (
						'". $this->con->real_escape_string($p->titulo) ."'
						,'". $this->con->real_escape_string($p->tipo) ."'
						,'". $this->con->real_escape_string($p->texto) ."'
						, current_timestamp
						,'". $this->con->real_escape_string($p->cod_usuario) ."'
						,'". $this->con->real_escape_string($p->cod_admin) ."' );";
				if($q = $this->con->query($str_q))
					return true;
				else
					throw new Exception("erro ao executar query<br />".$str_q);
			}
			else 
				throw new Exception("erro na conexao");	
		} catch (Exception $e) {
			erro_bd( $e, $this->con->error );
			return false;
		}
	}

	public function get($p){
		try {
			if($this->abreConexao()){
				$str_q = "
					SELECT 
						id, 
						titulo, 
						tipo, 
						texto, 
						data, 
						cod_usuario,
						cod_admin 
					FROM 
						usuario_nota 
					WHERE 
						ativo = 1
						AND id = '" . $this->con->real_escape_string( $p->id ) . "';";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->id = $obj->id;
						$p->titulo = $obj->titulo;
						$p->tipo = $obj->tipo;
						$p->texto = $obj->texto;
						if( is_null( $obj->data) ) $p->data = null;
						else $p->data = new DateTime( $obj->data );
						$p->cod_admin = $obj->cod_admin;
						$p->cod_usuario = $obj->cod_usuario;
						return true;
					}
					else return false;	
				}
				else
					throw new Exception("erro ao executar query<br />".$str_q);
			}
			else 
				throw new Exception("erro na conexao ao banco");	
		} catch (Exception $e) {
			erro_bd( $e, $this->con->error );
			return false;
		}
	}

	public function atualizar($p){
		try {
			if($this->abreConexao()){
				$str_q = 
					"UPDATE 
						usuario_nota
					SET 
						titulo = '". $this->con->real_escape_string($p->titulo) ."'
						, tipo = '". $this->con->real_escape_string($p->tipo) ."'
						, texto = '". $this->con->real_escape_string($p->texto) ."'
						, cod_usuario = '". $this->con->real_escape_string($p->cod_usuario) ."'
						, cod_admin = '". $this->con->real_escape_string($p->cod_admin) ."'
						, data = str_to_date('". $this->con->real_escape_string( $p->data->format('d/m/Y') ) ."','%d/%m/%Y')
					WHERE 
						id = '".$this->con->real_escape_string($p->id)."' 
						AND ativo = 1
						AND codprojeto = ".$this->codProjeto.";";
				if($q = $this->con->query($str_q))
					return true;
				else
					throw new Exception("erro ao executar query<br />".$str_q);
			}
			else 
				throw new Exception("erro na conexao");	
		} catch (Exception $e) {
			erro_bd( $e, $this->con->error );
			return false;
		}
	}	
	
	public function deletar(UsuarioNota $obj){
		try {
			if($this->abreConexao()){
				$str_q = "
						UPDATE usuario_nota
						SET ativo = 0
						WHERE 
							id = '".$this->con->real_escape_string($obj->id)."' 
							AND ativo = 1;";
				if($q = $this->con->query($str_q))
					return true;
				else
					throw new Exception("erro ao executar query<br />".$str_q);
			}
			else 
				throw new Exception("erro na conexao");	
		} catch (Exception $e) {
			erro_bd( $e, $this->con->error );
			return false;
		}
	}
}

?>