<?php

class EnqueteAlternativa{

	public $id;
	public $texto;
	public $cod_enquete;
	private $bd;
	
	public function EnqueteAlternativa(){
		$this->id = '';
		$this->texto = "";
		$this->cod_enquete = "";
		$this->bd = new EnqueteAlternativaDAO();
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
		$obj = new EnqueteAlternativa();
		$obj->id = $p_id;
		if ( $obj->get() ) 
			return $obj;
		else 
			return null;
	}
	public static function _deletar( $p_id ){
		$obj = new EnqueteAlternativa();
		$obj->id = $p_id;
		return ( $obj->deletar() ); 
	}

}

class EnqueteAlternativaDAO extends BaseDAO{
	
	public function cadastrar(EnqueteAlternativa $p){
		try {
			if($this->abreConexao()){
				$str_q = "
					INSERT INTO 
						enquete_alternativa( texto, cod_enquete, codprojeto )
					VALUES(
						'". $this->con->real_escape_string($p->texto) ."'
						,'". $this->con->real_escape_string($p->cod_enquete) ."'
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
						texto,
						cod_enquete
					FROM 
						enquete_alternativa 
					WHERE 
						ativo = 1
						AND codprojeto = " . $this->codProjeto ." 
						AND id = '" . $this->con->real_escape_string( $p->id ) . "';";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->id = $obj->id;
						$p->texto = $obj->texto;
						$p->cod_enquete = $obj->cod_enquete;
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
						texto,
						cod_enquete
					FROM 
						enquete_alternativa 
					WHERE 
						ativo = 1
						AND codprojeto = " . $this->codProjeto ." 
						AND texto = '" . $this->con->real_escape_string( $p->texto ) . "';";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->id = $obj->id;
						$p->texto = $obj->texto;
						$p->cod_enquete = $obj->cod_enquete;
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
						enquete_alternativa
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
	
	public function deletar(EnqueteAlternativa $obj){
		try {
			if($this->abreConexao()){
				$str_q = "
						UPDATE 
							enquete_alternativa
						SET 
							ativo = 0
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