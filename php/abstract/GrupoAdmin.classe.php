<?php

class GrupoAdmin{

	public $id;
	public $nome;
	public $descricao;
	public $cod_pagina_admin;
	
	private $bd;
	
	public function __construct(){
		$this->id = "";
		$this->nome = "";
		$this->descricao = "";
		$this->cod_pagina_admin = "";
		$this->bd = new GrupoAdminDAO();
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
	
	public function deletar(){
		return $this->bd->deletar($this);
	}
	// FIM CRUD	

	// estáticos
	public static function _get( $p_id ){
		$obj = new GrupoAdmin();
		$obj->id = $p_id;
		if ( $obj->get() ) return $obj;
		else return null;
	}
	
	public static function _deletar( $p_id ){
		$obj = new GrupoAdmin();
		$obj->id = $p_id;
		return ( $obj->deletar() );
	}

}

class GrupoAdminDAO extends BaseDAO{
	
	//CRUD
	public function cadastrar( GrupoAdmin $obj ){
		try {
			if($this->abreConexao()){
				$str_q = "
					INSERT INTO 
						grupo_admin( 
							nome,
							descricao,
							cod_pagina_admin,
							codprojeto
						)VALUES(
							'". $this->con->real_escape_string( $obj->nome ) ."'
							,'". $this->con->real_escape_string( $obj->descricao ) ."'
							,'". $this->con->real_escape_string( $obj->cod_pagina_admin ) ."'
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
					nome, 
					descricao,
					cod_pagina_admin
				FROM 
					grupo_admin
				WHERE 
					ativo = 1 
					AND codprojeto in ( ".$this->codProjeto." ) 
					AND id = '" . $this->con->real_escape_string( $obj->id ) . "';";					
				if( $q = $this->con->query( $str_q ) )
				{
					if( !$q->num_rows == 0 ){
						$reg = $q->fetch_object();
						$obj->id = $reg->id;
						$obj->nome = $reg->nome;
						$obj->descricao = $reg->descricao;
						$obj->cod_pagina_admin = $reg->cod_pagina_admin;
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
					UPDATE 
						grupo_admin
					SET 
						nome = '". $this->con->real_escape_string($obj->nome) ."'
						, descricao = '". $this->con->real_escape_string($obj->descricao) ."'
						, cod_pagina_admin = '". $this->con->real_escape_string($obj->cod_pagina_admin) ."'
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

	public function deletar( GrupoAdmin $obj ){
		try {
			if($this->abreConexao()){
				$str_q = "
					UPDATE 
						grupo_admin
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
