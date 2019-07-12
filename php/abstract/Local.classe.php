<?php

//Classe Local e LocalDAO
//06 de Maio de 2012

class Local{

	public $id;
	public $id_url;
	public $nome;
	public $descricao;
	public $imagem;
	public $capa;
	public $logo;
	public $endereco;
	public $cod_cidade;
	public $localizacao_latitude;
	public $localizacao_longitude;
	private $bd;
	
	public function Local(){
		$this->id = '';
		$this->nome = '';
		$this->id_url = "";
		$this->descricao = "";
		$this->imagem = "";
		$this->capa = "";
		$this->logo = "";
		$this->endereco = "";
		$this->cod_cidade = "";
		$this->localizacao_latitude = "";
		$this->localizacao_longitude = "";
		$this->bd = new LocalDAO();
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
		$obj = new Local();
		$obj->id = $p_id;
		if ( $obj->get() ) 
			return $obj;
		else 
			return null;
	}

	public static function _getByUrl( $p_idUrl ){
		$obj = new Local();
		$obj->id_url = $p_idUrl;
		if ( $obj->getByUrl() ) 
			return $obj;
		else 
			return null;
	}

	public static function _deletar( $p_id ){
		$obj = new Local();
		$obj->id = $p_id;
		return ( $obj->deletar() ); 
	}

}

class LocalDAO extends BaseDAO{
	
	public function cadastrar(Local $p){
		try {
			if($this->abreConexao()){
				$str_q = "
					INSERT INTO 
					local( 
						id_url, 
						nome, 
						descricao, 
						imagem, 
						capa, 
						logo, 
						endereco, 
						cod_cidade, 
						localizacao_latitude,
						localizacao_longitude, 
						codprojeto 
					) VALUES (
						'". $this->con->real_escape_string($p->id_url) ."'
						,'". $this->con->real_escape_string($p->nome) ."'
						,'". $this->con->real_escape_string($p->descricao) ."'
						,'". $this->con->real_escape_string($p->imagem) ."'
						,'". $this->con->real_escape_string($p->capa) ."'
						,'". $this->con->real_escape_string($p->logo) ."'
						,'". $this->con->real_escape_string($p->endereco) ."'
						,'". $this->con->real_escape_string($p->cod_cidade) ."'
						,'". $this->con->real_escape_string($p->localizacao_latitude) ."'
						,'". $this->con->real_escape_string($p->localizacao_longitude) ."'
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
						descricao, 
						imagem, 
						capa, 
						logo, 
						endereco, 
						cod_cidade, 
						localizacao_latitude,
						localizacao_longitude
					FROM 
						local 
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
						$p->descricao = $obj->descricao;
						$p->imagem = $obj->imagem;
						$p->capa = $obj->capa;
						$p->logo = $obj->logo;
						$p->endereco = $obj->endereco;
						$p->cod_cidade = $obj->cod_cidade;
						$p->localizacao_latitude = $obj->localizacao_latitude;
						$p->localizacao_longitude = $obj->localizacao_longitude;
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
						descricao, 
						imagem, 
						capa, 
						logo, 
						endereco, 
						cod_cidade, 
						localizacao_latitude,
						localizacao_longitude
					FROM 
						local 
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
						$p->descricao = $obj->descricao;
						$p->imagem = $obj->imagem;
						$p->capa = $obj->capa;
						$p->logo = $obj->logo;
						$p->endereco = $obj->endereco;
						$p->cod_cidade = $obj->cod_cidade;
						$p->localizacao_latitude = $obj->localizacao_latitude;
						$p->localizacao_longitude = $obj->localizacao_longitude;
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
						local
					SET 
						id_url = '". $this->con->real_escape_string($p->id_url) ."'
						, nome = '". $this->con->real_escape_string($p->nome) ."'
						, descricao = '". $this->con->real_escape_string($p->descricao) ."'
						, imagem = '". $this->con->real_escape_string($p->imagem) ."'
						, capa = '". $this->con->real_escape_string($p->capa) ."'
						, logo = '". $this->con->real_escape_string($p->logo) ."'
						, endereco = '". $this->con->real_escape_string($p->endereco) ."'
						, cod_cidade = '". $this->con->real_escape_string($p->cod_cidade) ."'
						, localizacao_latitude = '". $this->con->real_escape_string($p->localizacao_latitude) ."'
						, localizacao_longitude = '". $this->con->real_escape_string($p->localizacao_longitude) ."'
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
	
	public function deletar(Local $obj){
		try {
			if($this->abreConexao()){
				$str_q = "
						UPDATE local
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