<?php

class EnqueteResposta{

	public $id;
	public $cod_enquete_alternativa;
	public $cod_enquete;
	private $bd;
	
	public function EnqueteResposta(){
		$this->id = '';
		$this->cod_enquete_alternativa = "";
		$this->cod_enquete = "";
		$this->bd = new EnqueteRespostaDAO();
	}
	
	public function cadastrar(){
		return $this->bd->cadastrar($this);
	}

}

class EnqueteRespostaDAO extends BaseDAO{
	
	public function cadastrar(EnqueteResposta $p){
		try {
			if($this->abreConexao()){
				$str_q = "
					INSERT INTO 
						enquete_resposta( cod_enquete_alternativa, cod_enquete, codprojeto )
					VALUES(
						'". $this->con->real_escape_string($p->cod_enquete_alternativa) ."'
						,'". $this->con->real_escape_string($p->cod_enquete) ."'
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

}

?>