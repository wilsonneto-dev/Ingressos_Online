<?php

class Parceiro{

	public $id;
	public $id_url;
	public $nome;
	public $texto;
	public $foto;
	public $link;
	public $intro;
	private $bd;
	
	public function Parceiro(){
		$this->id = '';
		$this->nome = '';
		$this->id_url = "";
		$this->texto = "";
		$this->foto = "";
		$this->link = "";
		$this->intro = "";
		$this->bd = new ParceiroDAO();
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
		$obj = new Parceiro();
		$obj->id = $p_id;
		if ( $obj->get() ) 
			return $obj;
		else 
			return null;
	}

	public static function _getByUrl( $p_idUrl ){
		$obj = new Parceiro();
		$obj->id_url = $p_idUrl;
		if ( $obj->getByUrl() ) 
			return $obj;
		else 
			return null;
	}

	public static function _deletar( $p_id ){
		$obj = new Parceiro();
		$obj->id = $p_id;
		return ( $obj->deletar() ); 
	}

}

class ParceiroDAO extends BaseDAO{
	
	public function cadastrar(Parceiro $p){
		try {
			if($this->abreConexao()){
				$str_q = "
					INSERT INTO 
					parceiro( 
						id_url, 
						nome, 
						texto, 
						foto, 
						link, 
						intro,
						codprojeto 
					) VALUES (
						'". $this->con->real_escape_string($p->id_url) ."'
						,'". $this->con->real_escape_string($p->nome) ."'
						,'". $this->con->real_escape_string($p->texto) ."'
						,'". $this->con->real_escape_string($p->foto) ."'
						,'". $this->con->real_escape_string($p->link) ."'
						,'". $this->con->real_escape_string($p->intro) ."'
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
						id_url, 
						nome, 
						texto, 
						foto, 
						link, 
						intro 
					FROM 
						parceiro 
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
						$p->texto = $obj->texto;
						$p->foto = $obj->foto;
						$p->link = $obj->link;
						$p->intro = $obj->intro;
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
						texto, 
						foto, 
						link, 
						intro
					FROM 
						parceiro 
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
						$p->texto = $obj->texto;
						$p->foto = $obj->foto;
						$p->link = $obj->link;
						$p->intro = $obj->intro;
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
						parceiro
					SET 
						id_url = '". $this->con->real_escape_string($p->id_url) ."'
						, nome = '". $this->con->real_escape_string($p->nome) ."'
						, texto = '". $this->con->real_escape_string($p->texto) ."'
						, foto = '". $this->con->real_escape_string($p->foto) ."'
						, link = '". $this->con->real_escape_string($p->link) ."'
						, intro = '". $this->con->real_escape_string($p->intro) ."'
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
	
	public function deletar(Parceiro $obj){
		try {
			if($this->abreConexao()){
				$str_q = "
						UPDATE parceiro
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