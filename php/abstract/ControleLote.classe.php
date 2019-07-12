<?php

class ControleLote{

	public $id;
	public $titulo;
	public $data_entrar;
	public $data_sair;
	public $quantidade_limite;
	
	private $bd;
	
	public function __construct(){
		$this->id = "";
		$this->titulo = "";
		$this->data_entrar = "";
		$this->data_sair = "";
		$this->quantidade_limite = "";
		$this->bd = new ControleLoteDAO();
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
		$obj = new ControleLote();
		$obj->id = $p_id;
		if ( $obj->get() ) return $obj;
		else return null;
	}
	
	public static function _deletar( $p_id ){
		$obj = new ControleLote();
		$obj->id = $p_id;
		if ( $obj->deletar() ) return true;
		else return false;
	}
	

}

class ControleLoteDAO extends BaseDAO{
	
	//CRUD
	public function cadastrar( ControleLote $obj ){
		try {
			if($this->abreConexao()){
				$str_q = "
					INSERT INTO 
						controle_lote( 
							titulo,
							data_entrar,
							data_sair,
							quantidade_limite,
							codprojeto
						)VALUES(
							'". $this->con->real_escape_string( $obj->titulo ) ."'
							, str_to_date('". $this->con->real_escape_string( $p->data_entrar->format('d/m/Y') ) ."','%d/%m/%Y')
							, str_to_date('". $this->con->real_escape_string($p->data_sair->format('d/m/Y')) ."','%d/%m/%Y')
							,'". $this->con->real_escape_string( $obj->quantidade_limite ) ."'
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
					titulo, 
					data_entrar,
					data_sair,
					quantidade_limite
				FROM 
					controle_lote
				WHERE 
					ativo = 1 
					AND codprojeto in ( ".$this->codProjeto." ) 
					AND id = '" . $this->con->real_escape_string( $obj->id ) . "';";					
				if( $q = $this->con->query( $str_q ) )
				{
					if( !$q->num_rows == 0 ){
						$reg = $q->fetch_object();
						$obj->id = $reg->id;
						$obj->titulo = $reg->titulo;
						if( is_null( $obj->data_entrar) ) $p->data_entrar = null;
						else $p->data_entrar = new DateTime( $obj->data_entrar );
						if( is_null( $obj->data_sair) ) $p->data_sair = null;
						else $p->data_sair = new DateTime( $obj->data_sair );
						$obj->quantidade_limite = $reg->quantidade_limite;
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
						controle_lote
					SET 
						titulo = '". $this->con->real_escape_string($obj->titulo) ."'
						, data_entrar = str_to_date('". $this->con->real_escape_string( $p->data_entrar->format('d/m/Y') ) ."','%d/%m/%Y')
						, data_sair = str_to_date('". $this->con->real_escape_string($p->data_sair->format('d/m/Y')) ."','%d/%m/%Y')
						, quantidade_limite = '". $this->con->real_escape_string($obj->quantidade_limite) ."'
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

	public function deletar( ControleLote $obj ){
		try {
			if($this->abreConexao()){
				$str_q = "
					UPDATE 
						controle_lote
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
