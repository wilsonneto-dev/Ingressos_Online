<?php

class NotificacaoVisualizacao{

	public $id;
	public $cod_admin;
	public $cod_notificacao_tipo;
	public $data;
	private $bd;
	
	public function NotificacaoVisualizacao(){
		$this->id = '';
		$this->cod_notificacao_tipo = '';
		$this->cod_admin = "";
		$this->data = "";
		$this->bd = new NotificacaoVisualizacaoDAO();
	}
	
	public function cadastrar(){
		return $this->bd->cadastrar($this);
	}

	public function get(){
		return $this->bd->get($this);
	}

	public function atualizarData(){
		return $this->bd->atualizarData($this);
	}

	public static function _get( $p_cod_admin, $p_cod_notificacao_tipo ){
		$obj = new NotificacaoVisualizacao();
		$obj->cod_admin = $p_cod_admin;
		$obj->cod_notificacao_tipo = $p_cod_notificacao_tipo;
		if ( $obj->get() ) return $obj;
		else return null;
	}

	public static function _atualizar( $p_cod_admin, $p_cod_notificacao_tipo ){
		$obj = new NotificacaoVisualizacao();
		$obj->cod_admin = $p_cod_admin;
		$obj->cod_notificacao_tipo = $p_cod_notificacao_tipo;
		return ( $obj->atualizarData() );
	}

}

class NotificacaoVisualizacaoDAO extends BaseDAO{
	
	public function cadastrar(NotificacaoVisualizacao $p){
		try {
			if( $this->abreConexao() ){
				$str_q = "
					INSERT INTO notificacao_visualizacao( 
						cod_admin, 
						cod_notificacao_tipo, 
						data, 
						codprojeto 
					)VALUES(
						'". $this->con->real_escape_string($p->cod_admin) ."'
						,'". $this->con->real_escape_string($p->cod_notificacao_tipo) ."'
						, ( now() - interval 5 day ) 
						, ". $this->codProjeto.
					");";
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
						cod_admin, 
						cod_notificacao_tipo, 
						data
					FROM 
						notificacao_visualizacao 
					WHERE 
						cod_admin = '".$this->con->real_escape_string($p->cod_admin)."' 
						and cod_notificacao_tipo = '".$this->con->real_escape_string($p->cod_notificacao_tipo)."' 
						AND codprojeto = " . $this->codProjeto .";";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->id = $obj->id;
						$p->cod_admin = $obj->cod_admin;
						$p->cod_notificacao_tipo = $obj->cod_notificacao_tipo;
						if( is_null( $obj->data) ) $p->data = null;
						else $p->data = new DateTime( $obj->data );
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

	public function atualizarData($p){
		try {
			if($this->abreConexao()){
				$str_q = 
					"UPDATE 
						notificacao_visualizacao
					SET 
						cod_admin = '". $this->con->real_escape_string($p->cod_admin) ."'
						, cod_notificacao_tipo = '". $this->con->real_escape_string($p->cod_notificacao_tipo) ."'
						, data = CURRENT_TIMESTAMP
					WHERE 
						cod_admin = '".$this->con->real_escape_string($p->cod_admin)."' 
						AND cod_notificacao_tipo = '".$this->con->real_escape_string($p->cod_notificacao_tipo)."' 
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

}

?>