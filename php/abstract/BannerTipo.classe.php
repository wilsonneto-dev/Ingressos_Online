<?php

//Classe BannerTipo e BannerTipoDAO
//06 de Maio de 2012

class BannerTipo{

	public $id;
	public $nome;
	public $altura;
	public $largura;
	private $bd;
	
	public function BannerTipo(){
		$this->id = '';
		$this->nome = "";
		$this->altura = "";
		$this->largura = "";
		$this->bd = new BannerTipoDAO();
	}

	public function get(){
		return $this->bd->get($this);
	}

	// estáticos 
	public static function _get( $p_id ){
		$obj = new BannerTipo();
		$obj->id = $p_id;
		if ( $obj->get() ) 
			return $obj;
		else 
			return null;
	}


}

class BannerTipoDAO extends BaseDAO{
	
	public function get($p){
		try {
			if($this->abreConexao()){
				$str_q = "
					SELECT 
						id, 
						nome, 
						altura,
						largura,
						codprojeto						
					FROM 
						banner_tipo 
					WHERE 
						ativo = 1
						AND codprojeto = " . $this->codProjeto ." 
						AND id = '" . $this->con->real_escape_string( $p->id ) . "';";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->id = $obj->id;
						$p->nome = $obj->nome;
						$p->altura = $obj->altura;
						$p->largura = $obj->largura;
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

}

?>