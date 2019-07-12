<?php

class Meta{

	public $id;
	public $mes;
	public $ano;
	public $taxa_conversao;
	public $acessos;
	public $pedidos;
	public $aberturas;
	public $cadastros;
	public $acessos_dia;
	public $pedidos_dia;
	public $cadastros_dia;
	public $aberturas_dia;
	private $bd;
	
	public function Meta(){
		$this->id = "";
		$this->ano = "";
		$this->mes = "";
		$this->taxa_conversao = "";
		$this->acessos = "";
		$this->pedidos = "";
		$this->cadastros = "";
		$this->aberturas = "";
		$this->acessos_dia = "";
		$this->pedidos_dia = "";
		$this->cadastros_dia = "";
		$this->aberturas_dia = "";
		$this->bd = new MetaDAO();
	}
	
	public function cadastrar(){
		return $this->bd->cadastrar($this);
	}

	public function get(){
		return $this->bd->get($this);
	}

	public function getByAnoMes(){
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
		$obj = new Meta();
		$obj->id = $p_id;
		if ( $obj->get() ) 
			return $obj;
		else 
			return null;
	}

	public static function _getByAnoMes( $p_ano, $p_mes ){
		$obj = new Meta();
		$obj->ano = $p_ano;
		$obj->mes = $p_mes;
		if ( $obj->getByAnoMes() ) 
			return $obj;
		else 
			return null;
	}

	public static function _deletar( $p_id ){
		$obj = new Meta();
		$obj->id = $p_id;
		return ( $obj->deletar() ); 
	}


}

class MetaDAO extends BaseDAO{
	
	public function cadastrar(Meta $p){
		try {
			if($this->abreConexao()){
				$str_q = "
					INSERT INTO meta( 
						mes, 
						ano, 
						taxa_conversao, 
						acessos, 
						pedidos, 
						cadastros, 
						aberturas, 
						acessos_dia, 
						pedidos_dia, 
						cadastros_dia, 
						aberturas_dia, 
						codprojeto 
					) VALUES(
						'". $this->con->real_escape_string($p->mes) ."'
						,'". $this->con->real_escape_string($p->ano) ."'
						,'". $this->con->real_escape_string($p->taxa_conversao) ."'
						,'". $this->con->real_escape_string($p->acessos) ."'
						,'". $this->con->real_escape_string($p->pedidos) ."'
						,'". $this->con->real_escape_string($p->cadastros) ."'
						,'". $this->con->real_escape_string($p->aberturas) ."'
						,'". $this->con->real_escape_string($p->acessos_dia) ."'
						,'". $this->con->real_escape_string($p->pedidos_dia) ."'
						,'". $this->con->real_escape_string($p->cadastros_dia) ."'
						,'". $this->con->real_escape_string($p->aberturas_dia) ."'
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
						mes, 
						ano, 
						taxa_conversao, 
						acessos, 
						pedidos, 
						cadastros, 
						aberturas, 
						acessos_dia, 
						pedidos_dia, 
						cadastros_dia, 
						aberturas_dia, 
						codprojeto 
					FROM 
						meta 
					WHERE 
						ativo = 1
						AND codprojeto = " . $this->codProjeto ." 
						AND id = '" . $this->con->real_escape_string( $p->id ) . "';";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->id = $obj->id;
						$p->mes = $obj->mes;
						$p->ano = $obj->ano;
						$p->taxa_conversao = $obj->taxa_conversao;
						$p->acessos = $obj->acessos;
						$p->pedidos = $obj->pedidos;
						$p->cadastros = $obj->cadastros;
						$p->aberturas = $obj->aberturas;
						$p->acessos_dia = $obj->acessos_dia;
						$p->pedidos_dia = $obj->pedidos_dia;
						$p->cadastros_dia = $obj->cadastros_dia;
						$p->aberturas_dia = $obj->aberturas_dia;
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


	public function getByAnoMes($p){
		try {
			if($this->abreConexao()){
				$str_q = "
					SELECT 
						id, 
						mes, 
						ano, 
						taxa_conversao, 
						acessos, 
						pedidos, 
						cadastros, 
						aberturas, 
						acessos_dia, 
						pedidos_dia, 
						cadastros_dia, 
						aberturas_dia, 
						codprojeto 
					FROM 
						meta 
					WHERE 
						ativo = 1
						AND codprojeto = " . $this->codProjeto ." 
						AND mes = '" . $this->con->real_escape_string( $p->mes ) . "' 
						AND ano = '" . $this->con->real_escape_string( $p->ano ) . "';";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->id = $obj->id;
						$p->mes = $obj->mes;
						$p->ano = $obj->ano;
						$p->taxa_conversao = $obj->taxa_conversao;
						$p->acessos = $obj->acessos;
						$p->pedidos = $obj->pedidos;
						$p->cadastros = $obj->cadastros;
						$p->aberturas = $obj->aberturas;
						$p->acessos_dia = $obj->acessos_dia;
						$p->pedidos_dia = $obj->pedidos_dia;
						$p->cadastros_dia = $obj->cadastros_dia;
						$p->aberturas_dia = $obj->aberturas_dia;
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
						meta
					SET 
						mes = '". $this->con->real_escape_string($p->mes) ."'
						, ano = '". $this->con->real_escape_string($p->ano) ."'
						, taxa_conversao = '". $this->con->real_escape_string($p->taxa_conversao) ."'
						, acessos = '". $this->con->real_escape_string($p->acessos) ."'
						, pedidos = '". $this->con->real_escape_string($p->pedidos) ."'
						, cadastros = '". $this->con->real_escape_string($p->cadastros) ."'
						, aberturas = '". $this->con->real_escape_string($p->aberturas) ."'
						, acessos_dia = '". $this->con->real_escape_string($p->acessos_dia) ."'
						, pedidos_dia = '". $this->con->real_escape_string($p->pedidos_dia) ."'
						, cadastros_dia = '". $this->con->real_escape_string($p->cadastros_dia) ."'
						, aberturas_dia = '". $this->con->real_escape_string($p->aberturas_dia) ."'
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
	
	public function deletar(Meta $obj){
		try {
			if($this->abreConexao()){
				$str_q = "
						UPDATE meta
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