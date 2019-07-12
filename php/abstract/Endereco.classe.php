<?php

//Classe Endereco e EnderecoDAO
//06 de Maio de 2012

class Endereco{

	public $id;
	public $titulo;
	public $numero;
	public $rua;
	public $complemento;
	public $bairro;
	public $cod_cidade;
	public $cod_usuario;
	private $bd;
	
	public function Endereco(){
		$this->id = "";
		$this->titulo = "";
		$this->numero = "";
		$this->rua = "";
		$this->complemento = "";
		$this->bairro = "";
		$this->cod_cidade = "";
		$this->cod_usuario = "";
		$this->bd = new EnderecoDAO();
	}
	
	public function cadastrar(){ return $this->bd->cadastrar($this); }

	public function get(){ return $this->bd->get($this); }

	public function getListaByUsuario(){ return $this->bd->getListaByUsuario( $this ); }

	public function atualizar(){ return $this->bd->atualizar($this); }

	public function deletar(){
		return $this->bd->deletar($this);
	}

	public function deletarByUsuario(){
		return $this->bd->deletarByUsuario($this);
	}

	// estáticos 
	public static function _get( $p_id ){
		$obj = new Endereco();
		$obj->id = $p_id;
		if ( $obj->get() ) 
			return $obj;
		else 
			return null;
	}

	public static function _getByUsuario( $p_cod_usuario ){
		$obj = new Endereco();
		$obj->cod_usuario = $p_cod_usuario;
		if ( $obj->getByUsuario() ) 
			return $obj;
		else 
			return null;
	}
	
	public static function _getListaByUsuario( $p_cod_usuario ){
		$obj = new Endereco();
		$obj->cod_usuario = $p_cod_usuario;
		return $obj->getListaByUsuario(); 
	}

	public static function _deletar( $p_id ){
		$obj = new Endereco();
		$obj->id = $p_id;
		if ( $obj->deletar() ) 
			return true;
		else 
			return false;
	}
	
	public static function _deletarByUsuario( $p_titulo ){
		$obj = new Endereco();
		$obj->titulo = $p_titulo;
		if ( $obj->deletarByUsuario() ) 
			return true;
		else 
			return false;
	}

}

class EnderecoDAO extends BaseDAO{
	
	public function cadastrar(Endereco $p){
		try {
			if($this->abreConexao()){
				$str_q = "
					INSERT INTO 
						endereco( 
							titulo, 
							numero, 
							rua, 
							complemento,
							bairro,
							cod_cidade,
							cod_usuario,
							codprojeto 
					)VALUES(
						'". $this->con->real_escape_string($p->titulo) ."'
						,'". $this->con->real_escape_string($p->numero) ."'
						,'". $this->con->real_escape_string($p->rua) ."'
						,'". $this->con->real_escape_string($p->complemento) ."'
						,'". $this->con->real_escape_string($p->bairro) ."'
						,'". $this->con->real_escape_string($p->cod_cidade) ."'
						,'". $this->con->real_escape_string($p->cod_usuario) ."'
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
						titulo, 
						numero, 
						rua, 
						complemento,
						bairro,
						cod_cidade,
						cod_usuario
					FROM 
						endereco 
					WHERE 
						codprojeto = " . $this->codProjeto ." 
						AND id = '" . $this->con->real_escape_string( $p->id ) . "';";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->id = $obj->id;
						$p->titulo = $obj->titulo;
						$p->numero = $obj->numero;
						$p->rua = $obj->rua;
						$p->complemento = $obj->complemento;
						$p->bairro = $obj->bairro;
						$p->cod_cidade = $obj->cod_cidade;
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
			erro_bd( $exception, $this->con->error );
			return false;
		}
	}

	public function getByUsuario($p){
		try {
			if($this->abreConexao()){
				$str_q = "
					SELECT 
						id, 
						titulo, 
						numero, 
						rua, 
						complemento,
						bairro,
						cod_cidade,
						cod_usuario
					FROM 
						endereco 
					WHERE 
						codprojeto = " . $this->codProjeto ." 
						AND cod_usuario = '" . $this->con->real_escape_string( $p->cod_usuario ) . "';";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->id = $obj->id;
						$p->titulo = $obj->titulo;
						$p->numero = $obj->numero;
						$p->rua = $obj->rua;
						$p->complemento = $obj->complemento;
						$p->bairro = $obj->bairro;
						$p->cod_cidade = $obj->cod_cidade;
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
			erro_bd( $exception, $this->con->error );
			return false;
		}
	}

	public function getListaByUsuario( $endereco ){
		try {
			if( $this->abreConexao() ){
				$str_q = "
					SELECT 
						titulo, 
						numero, 
						rua, 
						complemento,
						bairro,
						cod_cidade,
						cod_usuario,
						codprojeto 
					FROM 
						endereco 
					WHERE 
						codprojeto = " . $this->codProjeto ."
						and cod_usuario = '".$this->con->real_escape_string( $endereco->cod_usuario )."';";					
				if($q = $this->con->query($str_q)){
					$lista = array();
					if( $q->num_rows > 0){
						while( ( $obj = $q->fetch_object() ) != false )
						{
							$p = new Endereco();
							$p->id = $obj->id;
							$p->titulo = $obj->titulo;
							$p->numero = $obj->numero;
							$p->rua = $obj->rua;
							$p->complemento = $obj->complemento;
							$p->bairro = $obj->bairro;
							$p->cod_cidade = $obj->cod_cidade;
							$p->cod_usuario = $obj->cod_usuario;
							$lista[] = $p;
						}
					}
					return $lista;	
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

	public function atualizar( $p ){
		try {
			if($this->abreConexao()){
				$str_q = 
					"UPDATE 
						endereco
					SET 
						titulo = '". $this->con->real_escape_string($p->titulo) ."',
						numero = '". $this->con->real_escape_string($p->numero) ."',
						rua = '". $this->con->real_escape_string($p->rua) ."',
						complemento = '". $this->con->real_escape_string($p->complemento) ."',
						bairro = '". $this->con->real_escape_string($p->bairro) ."',
						cod_cidade = '". $this->con->real_escape_string($p->cod_cidade) ."'
					WHERE 
						id = '".$this->con->real_escape_string( $p->id )."' 
						AND codprojeto = ". $this->codProjeto .";";
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
	
	public function deletar(Endereco $obj){
		try {
			if($this->abreConexao()){
				$str_q = "
						DELETE 
							endereco
						WHERE 
							id = '" . $this->con->real_escape_string( $obj->id ) . "' 
							AND codprojeto = ".$this->codProjeto.";";
				if($q = $this->con->query($str_q))
					return true;
				else
					throw new Exception( "erro ao executar query<br />" . $str_q );
			}
			else 
				throw new Exception("erro na conexao");	
		} catch ( Exception $e ) {
			erro_bd( $exception, $this->con->error );
			return false;
		}
	}

	public function deletarByUsuario(Endereco $obj){
		try {
			if($this->abreConexao()){
				$str_q = "
						DELETE 
							endereco
						WHERE 
							cod_usuario = '" . $this->con->real_escape_string( $obj->cod_usuario ) . "' 
							AND codprojeto = ".$this->codProjeto.";";
				if($q = $this->con->query($str_q))
					return true;
				else
					throw new Exception( "erro ao executar query<br />" . $str_q );
			}
			else 
				throw new Exception("erro na conexao");	
		} catch ( Exception $e ) {
			erro_bd( $exception, $this->con->error );
			return false;
		}
	}

}

?>