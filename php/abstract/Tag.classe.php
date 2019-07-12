<?php

class Tag{

	public $id;
	public $texto;
	private $bd;
	
	public function Tag(){
		$this->id = '';
		$this->texto = "";
		$this->bd = new TagDAO();
	}
	
	public function cadastrar(){
		return $this->bd->cadastrar($this);
	}

	public function get(){
		return $this->bd->get($this);
	}

	public function getByTexto(){
		return $this->bd->getByTexto($this);
	}

	public function atualizar(){
		return $this->bd->atualizar($this);
	}

	public function deletar(){
		return $this->bd->deletar($this);
	}

	// estáticos 
	public static function _get( $p_id ){
		$obj = new Tag();
		$obj->id = $p_id;
		if ( $obj->get() ) 
			return $obj;
		else 
			return null;
	}

	public static function _getByTexto( $p_idTexto ){
		$obj = new Tag();
		$obj->texto = $p_idTexto;
		if ( $obj->getByTexto() ) 
			return $obj;
		else 
			return null;
	}


}

class TagDAO extends BaseDAO{
	
	public function cadastrar(Tag $p){
		try {
			if($this->abreConexao()){
				$str_q = "
					INSERT INTO 
						tag( texto, codprojeto )
					VALUES(
						'". $this->con->real_escape_string($p->texto) ."'
						, ". $this->codProjeto.
					");";
				if($q = $this->con->query($str_q)){
					$p->id = $this->con->insert_id;
					return true;
				}
				else
					throw new Exception("erro ao executar query<br />".$str_q);
			}
			else 
				throw new Exception("erro na conexao");	
		} catch (Exception $e) {
			erro_bd( $exception, $this->con->error );
			return false;
		}
	}

	public function get($p){
		try {
			if($this->abreConexao()){
				$str_q = "
					SELECT 
						id, 
						texto 
					FROM 
						tag 
					WHERE 
						ativo = 1
						AND codprojeto = " . $this->codProjeto ." 
						AND id = '" . $this->con->real_escape_string( $p->id ) . "';";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->id = $obj->id;
						$p->texto = $obj->texto;
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
			erro_bd( $exception, $this->con->error );
			return false;
		}
	}

	public function getByTexto($p){
		try {
			if($this->abreConexao()){
				$str_q = "
					SELECT 
						id, 
						texto 
					FROM 
						tag 
					WHERE 
						ativo = 1
						AND codprojeto = " . $this->codProjeto ." 
						AND texto = '" . $this->con->real_escape_string( $p->texto ) . "';";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->id = $obj->id;
						$p->texto = $obj->texto;
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
			erro_bd( $exception, $this->con->error );
			return false;
		}
	}

	public function atualizar($p){
		try {
			if($this->abreConexao()){
				$str_q = 
					"UPDATE 
						tag
					SET 
						texto = '". $this->con->real_escape_string($p->texto) ."'
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
			erro_bd( $exception, $this->con->error );
			return false;
		}
	}	
	
	public function deletar(Tag $obj){
		try {
			if($this->abreConexao()){
				$str_q = "
						UPDATE tag
						SET ativo = 0
						WHERE 
							id = '".$this->con->real_escape_string($obj->id)."' 
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
			erro_bd( $exception, $this->con->error );
			return false;
		}
	}
}

?>