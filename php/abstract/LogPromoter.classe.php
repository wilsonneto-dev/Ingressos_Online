<?php

class LogPromoter {

	public $id;
	public $ip;
	public $pagina;
	public $texto;
	public $tipo;
	public $cod_promoter;

	private $bd;
	
	public function LogPromoter(){
		$this->id = '';
		$this->pagina = '';
		$this->ip = "";
		$this->texto = "";
		$this->tipo = "";
		$this->cod_promoter = "";
		$this->bd = new LogPromoterDAO();
	}
	
	public function cadastrar(){
		return $this->bd->cadastrar($this);
	}

	public static function _salvar(
		$p_log = "",
		$p_tipo = "",
		$p_cod_promoter = ""
	){
		$log = new LogPromoter();
		$log->ip = $_SERVER['REMOTE_ADDR'];
		$log->pagina = $_SERVER['REQUEST_URI'];
		$log->texto = $p_log;
		$log->tipo = $p_tipo;
		$log->cod_promoter = $p_cod_promoter;
		$log->cadastrar();
	}
}

class LogPromoterDAO extends BaseDAO {
	
	public function cadastrar(LogPromoter $p){
		try {
			if($this->abreConexao()){
				$str_q = "
					INSERT INTO log_promoter( 
						ip, 
						pagina, 
						texto, 
						tipo, 
						cod_promoter, 
						codprojeto 	
					) VALUES (
						'". $this->con->real_escape_string( $p->ip ) ."'
						,'". $this->con->real_escape_string( $p->pagina ) ."'
						,'". $this->con->real_escape_string( $p->texto ) ."'
						,'". $this->con->real_escape_string( $p->tipo ) ."'
						,'". $this->con->real_escape_string( $p->cod_promoter ) ."'
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

}

?>