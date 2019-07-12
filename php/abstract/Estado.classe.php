<?php

//Classe Estado e EstadoDAO
//06 de Maio de 2012

class Estado{

	public $id;
	public $id_url;
	public $nome;
	public $uf;
	private $bd;
	
	public function Estado(){
		$this->id = '';
		$this->nome = '';
		$this->id_url = "";
		$this->uf = "";
		$this->bd = new EstadoDAO();
	}
	
	public function cadastrar(){
		return $this->bd->cadastrar($this);
	}

	public function get(){
		return $this->bd->get($this);
	}

	public function getByUrl(){
		return $this->bd->getByUrl($this);
	}

	public function atualizar(){
		return $this->bd->atualizar($this);
	}

	public function deletar(){
		return $this->bd->deletar($this);
	}

	// estáticos 
	public static function _get( $p_id ){
		$obj = new Estado();
		$obj->id = $p_id;
		if ( $obj->get() ) 
			return $obj;
		else 
			return null;
	}

	public static function _getByUrl( $p_idUrl ){
		$obj = new Estado();
		$obj->id_url = $p_idUrl;
		if ( $obj->getByUrl() ) 
			return $obj;
		else 
			return null;
	}


}

class EstadoDAO extends BaseDAO{
	
	public function cadastrar(Estado $p){
		try {
			if($this->abreConexao()){
				$str_q = "
					INSERT INTO 
						estado( id_url, nome, uf, codprojeto )
					VALUES('". $this->con->real_escape_string($p->id_url) ."'
						,'". $this->con->real_escape_string($p->nome) ."'
						,'". $this->con->real_escape_string($p->uf) ."'
						,'". $this->con->real_escape_string($p->codprojeto) ."'
						, ". $this->codProjeto.
					");";
				if($q = $this->con->query($str_q)){
					$p->id = $this->con->insert_id();
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
						id_url, 
						nome, 
						uf
					FROM 
						estado 
					WHERE 
						ativo = 1
						AND codprojeto = " . $this->codProjeto ." 
						AND id = '" . $this->con->real_escape_string( $p->id ) . "';";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->id = $obj->id;
						$p->id_url = $obj->id_url;
						$p->nome = $obj->nome;
						$p->uf = $obj->uf;
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

	public function getByUrl($p){
		try {
			if($this->abreConexao()){
				$str_q = "
					SELECT 
						id, 
						id_url, 
						nome, 
						uf
					FROM 
						estado 
					WHERE 
						ativo = 1
						AND codprojeto = " . $this->codProjeto ." 
						AND id_url = '" . $this->con->real_escape_string( $p->id_url ) . "';";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->id = $obj->id;
						$p->id_url = $obj->id_url;
						$p->nome = $obj->nome;
						$p->uf = $obj->uf;
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
						estado
					SET 
						id_url = '". $this->con->real_escape_string($p->id_url) ."'
						, nome = '". $this->con->real_escape_string($p->nome) ."'
						, uf = '". $this->con->real_escape_string($p->uf) ."'
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
	
	public function deletar(Estado $obj){
		try {
			if($this->abreConexao()){
				$str_q = "
						UPDATE estado
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