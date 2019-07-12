<?php

class Duvida{

	public $id;
	public $titulo;
	public $texto;
	public $ordem;
	
	private $bd;
	
	public function __construct(){
		$this->id = "";
		$this->titulo = "";
		$this->texto = "";
		$this->ordem = "";
		$this->bd = new DuvidaDAO();
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
		$obj = new Duvida();
		$obj->id = $p_id;
		if ( $obj->get() ) return $obj;
		else return null;
	}
	
	public static function _deletar( $p_id ){
		$obj = new Duvida();
		$obj->id = $p_id;
		if ( $obj->deletar() ) return true;
		else return false;
	}
	

}

class DuvidaDAO extends BaseDAO{
	
	//CRUD
	public function cadastrar( Duvida $obj ){
		try {
			if($this->abreConexao()){
				$str_q = "
					INSERT INTO 
						duvida( 
							titulo,
							texto,
							ordem,
							codprojeto
						)VALUES(
							'". $this->con->real_escape_string( $obj->titulo ) ."'
							,'". $this->con->real_escape_string( $obj->texto ) ."'
							,'". $this->con->real_escape_string( $obj->ordem ) ."'
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
					ordem, 
					texto
				FROM 
					duvida
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
						$obj->texto = $reg->texto;
						$obj->ordem = $reg->ordem;
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
						duvida
					SET 
						titulo = '". $this->con->real_escape_string($obj->titulo) ."'
						, ordem = '". $this->con->real_escape_string($obj->ordem) ."'
						, texto = '". $this->con->real_escape_string($obj->texto) ."'
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

	public function deletar( Duvida $obj ){
		try {
			if($this->abreConexao()){
				$str_q = "
					UPDATE 
						duvida
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
