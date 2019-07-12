<?php

class Novidade{

	public $id;
	public $id_url;
	public $titulo;
	public $texto;
	public $imagem;
	public $thumb;
	public $intro;
	private $bd;
	
	public function Novidade(){
		$this->id = '';
		$this->titulo = '';
		$this->id_url = "";
		$this->texto = "";
		$this->imagem = "";
		$this->thumb = "";
		$this->intro = "";
		$this->bd = new NovidadeDAO();
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
		$obj = new Novidade();
		$obj->id = $p_id;
		if ( $obj->get() ) 
			return $obj;
		else 
			return null;
	}

	public static function _getByUrl( $p_idUrl ){
		$obj = new Novidade();
		$obj->id_url = $p_idUrl;
		if ( $obj->getByUrl() ) 
			return $obj;
		else 
			return null;
	}

	public static function _deletar( $p_id ){
		$obj = new Novidade();
		$obj->id = $p_id;
		return ( $obj->deletar() ); 
	}

}

class NovidadeDAO extends BaseDAO{
	
	public function cadastrar(Novidade $p){
		try {
			if($this->abreConexao()){
				$str_q = "
					INSERT INTO 
					novidade( 
						id_url, 
						titulo, 
						texto, 
						imagem, 
						thumb, 
						intro,
						codprojeto 
					) VALUES (
						'". $this->con->real_escape_string($p->id_url) ."'
						,'". $this->con->real_escape_string($p->titulo) ."'
						,'". $this->con->real_escape_string($p->texto) ."'
						,'". $this->con->real_escape_string($p->imagem) ."'
						,'". $this->con->real_escape_string($p->thumb) ."'
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
						titulo, 
						texto, 
						imagem, 
						thumb, 
						intro 
					FROM 
						novidade 
					WHERE 
						ativo = 1
						AND codprojeto = " . $this->codProjeto ." 
						AND id = '" . $this->con->real_escape_string( $p->id ) . "';";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->id = $obj->id;
						$p->id_url = $obj->id_url;
						$p->titulo = $obj->titulo;
						$p->texto = $obj->texto;
						$p->imagem = $obj->imagem;
						$p->thumb = $obj->thumb;
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
						titulo, 
						texto, 
						imagem, 
						thumb, 
						intro
					FROM 
						novidade 
					WHERE 
						ativo = 1
						AND codprojeto = " . $this->codProjeto ." 
						AND id_url = '" . $this->con->real_escape_string( $p->id_url ) . "';";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->id = $obj->id;
						$p->id_url = $obj->id_url;
						$p->titulo = $obj->titulo;
						$p->texto = $obj->texto;
						$p->imagem = $obj->imagem;
						$p->thumb = $obj->thumb;
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
						novidade
					SET 
						id_url = '". $this->con->real_escape_string($p->id_url) ."'
						, titulo = '". $this->con->real_escape_string($p->titulo) ."'
						, texto = '". $this->con->real_escape_string($p->texto) ."'
						, imagem = '". $this->con->real_escape_string($p->imagem) ."'
						, thumb = '". $this->con->real_escape_string($p->thumb) ."'
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
	
	public function deletar(Novidade $obj){
		try {
			if($this->abreConexao()){
				$str_q = "
						UPDATE novidade
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