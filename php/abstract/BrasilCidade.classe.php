<?php

//Classe BrasilCidade e BrasilCidadeDAO
//06 de Maio de 2012

class BrasilCidade{

	public $id;
	public $codigo_cidade;
	public $cidade;
	public $uf;

	private $bd;
	
	public function BrasilCidade(){
		$this->id = '';
		$this->cidade = '';
		$this->codigo_cidade = "";
		$this->uf = "";
		$this->bd = new BrasilCidadeDAO();
	}
	
	public function get(){
		return $this->bd->get($this);
	}

	// estáticos 
	public static function _get( $p_codigo_cidade ){
		$obj = new BrasilCidade();
		$obj->codigo_cidade = $p_codigo_cidade;
		if ( $obj->get() ) 
			return $obj;
		else 
			return null;
	}

}

class BrasilCidadeDAO extends BaseDAO{

	public function get($p){
		try {
			if($this->abreConexao()){
				$str_q = "
					SELECT 
						id, 
						codigo_cidade, 
						cidade, 
						uf
					FROM 
						brasil_cidade 
					WHERE 
						codigo_cidade = '" . $this->con->real_escape_string( $p->codigo_cidade ) . "';";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->id = $obj->id;
						$p->codigo_cidade = $obj->codigo_cidade;
						$p->cidade = $obj->cidade;
						$p->uf = $obj->uf;
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

}

?>