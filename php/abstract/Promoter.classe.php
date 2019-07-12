<?php

class Promoter{

	public $id;
	public $responsavel;
	public $email;
	public $senha;
	public $ultimo_acesso;
	public $ip;
	public $bloqueado;
	public $imagem;
	public $razao_social;
	
	private $bd;
	
	public function __construct(){
		$this->id = "";
		$this->responsavel = "";
		$this->email = "";
		$this->senha = "";
		$this->ultimo_acesso = null;
		$this->ip = "";
		$this->bloqueado = "";
		$this->imagem = "";
		$this->razao_social = "";
		$this->bd = new PromoterDAO();
	}
	
	public function logar(){
		return $this->bd->logar( $this );
	}

	public function existe(){
		return $this->bd->existe( $this );
	}
	
	//CRUD
	public function cadastrar(){
		return $this->bd->cadastrar($this);
	}
	public function get(){
		return $this->bd->get($this);
	}
	public function atualizar(){
		return $this->bd->atualizar($this);
	}
	public function atualizar_senha(){
		return $this->bd->atualizar_senha($this);
	}
	public function atualizar_ultimo_acesso(){
		return $this->bd->atualizar_ultimo_acesso($this);
	}
	public function deletar(){
		return $this->bd->deletar($this);
	}
	// FIM CRUD	

	// estáticos
	public static function _logar( $p_email , $p_senha ){
		$obj = new Promoter();
		$obj->email = $p_email;
		$obj->senha = $p_senha;
		if( $obj->logar() ) return $obj;
		else return null;
	}
	
	public static function _existe( $p_email ){
		$obj = new Promoter();
		$obj->email = $p_email;
		return $obj->existe();
	}
	
	public static function _get( $p_id ){
		$obj = new Promoter();
		$obj->id = $p_id;
		if ( $obj->get() ) return $obj;
		else return null;
	}
	

}

class PromoterDAO extends BaseDAO{
	
	public function logar(Promoter $obj){
		try {
			if($this->abreConexao()){
				$str_q = "
				SELECT 
					id, 
					responsavel, 
					email,
					senha,
					ultimo_acesso,
					ip,
					bloqueado,
					imagem,
					razao_social
				FROM promoter
				WHERE 
					ativo = 1 
					AND bloqueado = 0 
					AND codprojeto in ( ".$this->codProjeto." , 0 ) 
					AND email = '" . $this->con->real_escape_string( $obj->email ) . "' 
					AND senha = MD5('".$this->con->real_escape_string( $obj->senha ). "');";					
				if( $q = $this->con->query($str_q) ){
					if(!$q->num_rows == 0){
						$reg = $q->fetch_object();
						$obj->id = $reg->id;
						$obj->responsavel = $reg->responsavel;
						$obj->senha = $reg->senha;
						if( is_null( $reg->ultimo_acesso) ) $obj->ultimo_acesso = null;
						else $obj->ultimo_acesso = new DateTime( $reg->ultimo_acesso );
						if( is_null( $reg->ip) ) $obj->ip = null;
						else $obj->ip = $reg->ip;
						$obj->bloqueado = $reg->bloqueado;
						$obj->imagem = $reg->imagem;
						$obj->razao_social = $reg->razao_social;
						return true;
					}
					return false;	
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
	
	public function existe(Promoter $obj){
		try {
			if($this->abreConexao()){
				$str_q = "
					SELECT id
					FROM promoter
					WHERE 
						ativo = 1 
						AND codprojeto = ".$this->codProjeto."
						AND email = '" . $this->con->real_escape_string($obj->email) . "'"; 
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						return true;
					}
					return false;	
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
	
	//CRUD
	public function cadastrar( Promoter $obj ){
		try {
			if($this->abreConexao()){
				$str_q = "
					INSERT INTO 
						promoter( 
							responsavel,
							email,
							senha,
							imagem,
							razao_social,
							codprojeto
						)VALUES(
							'". $this->con->real_escape_string( $obj->responsavel ) ."'
							,'". $this->con->real_escape_string( $obj->email ) ."'
							,md5('". $this->con->real_escape_string( $obj->senha ) ."')
							,'". $this->con->real_escape_string( $obj->imagem ) ."'
							,'". $this->con->real_escape_string( $obj->razao_social ) ."'
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

	public function get( $obj ){
		try {
			if($this->abreConexao()){
				$str_q = 
				" SELECT 
				 	id, 
					responsavel, 
					email,
					senha,
					ultimo_acesso,
					ip,
					bloqueado,
					imagem,
					razao_social
				FROM 
					promoter
				WHERE 
					ativo = 1 
					AND codprojeto in ( ".$this->codProjeto." ) 
					AND id = '" . $this->con->real_escape_string( $obj->id ) . "';";					
				if($q = $this->con->query($str_q)){
					if( !$q->num_rows == 0 ){
						$reg = $q->fetch_object();
						$obj->id = $reg->id;
						$obj->responsavel = $reg->responsavel;
						$obj->senha = $reg->senha;
						$obj->email = $reg->email;
						if( is_null( $reg->ultimo_acesso) ) $obj->ultimo_acesso = null;
						else $obj->ultimo_acesso = new DateTime( $reg->ultimo_acesso );
						if( is_null( $reg->ip) ) $obj->ip = null;
						else $obj->ip = $reg->ip;
						$obj->bloqueado = $reg->bloqueado;
						$obj->imagem = $reg->imagem;
						$obj->razao_social = $reg->razao_social;
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

	public function atualizar( $obj ){
		try {
			if($this->abreConexao()){
				$str_q = "
					UPDATE promoter
					SET 
						responsavel = '". $this->con->real_escape_string($obj->responsavel) ."'
						, email = '". $this->con->real_escape_string($obj->email) ."'
						, bloqueado = '". $this->con->real_escape_string($obj->bloqueado) ."'
						, imagem = '". $this->con->real_escape_string($obj->imagem) ."'
						, razao_social = '". $this->con->real_escape_string($obj->razao_social) ."'
					WHERE 
						id = '".$this->con->real_escape_string( $obj->id )."'
						AND ativo = 1
						AND codprojeto = " . $this->codProjeto . ";";
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

	public function atualizar_senha( $obj ){
		try {
			if($this->abreConexao()){
				$str_q = "
					UPDATE 
						promoter
					SET 
						senha = md5('". $this->con->real_escape_string( $obj->senha ) ."')
					WHERE 
						id = '".$this->con->real_escape_string( $obj->id )."'
						AND ativo = 1
						AND codprojeto = " . $this->codProjeto . ";";
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
	
	public function atualizar_ultimo_acesso( $obj ){
		try {
			if($this->abreConexao()){
				$str_q = "
					UPDATE 
						promoter
					SET 
						ip = '".$this->con->real_escape_string( $_SERVER['REMOTE_ADDR'] )."',
						ultimo_acesso = CURRENT_TIMESTAMP
					WHERE 
						id = '".$this->con->real_escape_string( $obj->id )."'
						AND ativo = 1
						AND codprojeto = " . $this->codProjeto . ";";
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

	public function deletar( Promoter $obj ){
		try {
			if($this->abreConexao()){
				$str_q = "
					UPDATE 
						promoter
					SET 
						ativo = 0
					WHERE 
						id = '".$this->con->real_escape_string( $obj->id )."'
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

}
?>
