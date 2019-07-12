<?php

class EventoFoto{

	public $id;
	public $imagem;
	public $descricao;
	public $thumb;
	public $cod_evento;
	
	private $bd;
	
	public function __construct(){
		$this->id = "";
		$this->imagem = "";
		$this->descricao = "";
		$this->thumb = "";
		$this->cod_evento = "";
		$this->bd = new EventoFotoDAO();
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
		$obj = new EventoFoto();
		$obj->id = $p_id;
		if ( $obj->get() ) return $obj;
		else return null;
	}
	
	public static function _deletar( $p_id ){
		$obj = new EventoFoto();
		$obj->id = $p_id;
		if ( $obj->deletar() ) return true;
		else return false;
	}
}

class EventoFotoDAO extends BaseDAO {
	
	//CRUD
	public function cadastrar( EventoFoto $obj ){
		try {
			if( $this->abreConexao() ){
				$str_q = "
					INSERT INTO 
						evento_foto( 
							imagem,
							descricao,
							thumb,
							cod_evento,
							codprojeto
						)VALUES(
							'". $this->con->real_escape_string( $obj->imagem ) ."'
							, '". $this->con->real_escape_string( $p->descricao ) ."'
							,'". $this->con->real_escape_string( $obj->thumb ) ."'
							,'". $this->con->real_escape_string( $obj->cod_evento ) ."'
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
					imagem, 
					descricao,
					thumb,
					cod_evento
				FROM 
					evento_foto
				WHERE 
					ativo = 1 
					AND codprojeto in ( ".$this->codProjeto." ) 
					AND id = '" . $this->con->real_escape_string( $obj->id ) . "';";					
				if( $q = $this->con->query( $str_q ) )
				{
					if( !$q->num_rows == 0 ){
						$reg = $q->fetch_object();
						$obj->id = $reg->id;
						$obj->imagem = $reg->imagem;
						$obj->descricao = $reg->descricao;
						$obj->thumb = $reg->thumb;
						$obj->cod_evento = $reg->cod_evento;
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
						evento_foto
					SET 
						imagem = '". $this->con->real_escape_string($obj->imagem) ."'
						, descricao = '". $this->con->real_escape_string( $p->descricao ) ."'
						, thumb = '". $this->con->real_escape_string($obj->thumb) ."'
						, cod_evento = '". $this->con->real_escape_string( $obj->cod_evento ) ."'
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

	public function deletar( EventoFoto $obj ){
		try {
			if($this->abreConexao()){
				$str_q = "
					UPDATE 
						evento_foto
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
