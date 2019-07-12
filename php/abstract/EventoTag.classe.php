<?php

//Classe EventoTag e EventoTagDAO
//06 de Maio de 2012

class EventoTag{

	public $id;
	public $cod_evento;
	public $cod_tag;
	private $bd;
	
	public function EventoTag(){
		$this->id = "";
		$this->cod_evento = "";
		$this->cod_tag = "";
		$this->bd = new EventoTagDAO();
	}
	
	public function cadastrar(){ return $this->bd->cadastrar($this); }

	public static function deletarByEvento( $id_evento ){
		return (new EventoTagDAO())->deletarByEvento($id_evento);
	}
	public static function getByEvento( $id_evento ){
		return (new EventoTagDAO())->getByEvento($id_evento);
	}

}

class EventoTagDAO extends BaseDAO{
	
	public function cadastrar(EventoTag $p){
		try {
			if($this->abreConexao()){
				$str_q = "
					INSERT INTO 
					evento_tag ( 
						cod_evento, 
						cod_tag
					) VALUES (
						'" . $this->con->real_escape_string( $p->cod_evento ) . "'
						,'" . $this->con->real_escape_string( $p->cod_tag ) . "'
					);";
				if( $q = $this->con->query( $str_q ) )
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

	public function deletarByEvento($id){
		try {
			if($this->abreConexao()){
				$str_q = "
						DELETE FROM
							evento_tag
						WHERE 
							cod_evento = '" . $id . "';";
				if($q = $this->con->query($str_q))
					return true;
				else
					throw new Exception( "erro ao executar query<br />" . $str_q );
			}
			else 
				throw new Exception("erro na conexao");	
		} catch ( Exception $e ) {
			erro_bd( $e, $this->con->error );
			return false;
		}
	}

	public function getByEvento($id){
		try {
			if($this->abreConexao()){
				$str_q = "
						SELECT 
							GROUP_CONCAT( CONCAT(UCASE(LEFT(texto, 1)), SUBSTRING(texto, 2)) ) AS texto
						FROM
							evento_tag rel 
						INNER JOIN tag
							ON tag.id = rel.cod_tag
						WHERE 
							rel.cod_evento = '" . $id . "';";
				if($q = $this->con->query($str_q)){
					if($q->num_rows != 0){
						$obj = $q->fetch_object();
						return $obj->texto;
					}
					else return "";	
				}
				else
					throw new Exception( "erro ao executar query<br />" . $str_q );
			}
			else 
				throw new Exception("erro na conexao");	
		} catch ( Exception $e ) {
			erro_bd( $e, $this->con->error );
			return false;
		}
	}

}

?>