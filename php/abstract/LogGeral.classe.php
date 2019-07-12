<?php

class LogGeral {

	public $id;
	public $ip;
	public $pagina;
	public $texto;
	public $tipo;
	
	private $bd;
	
	public function LogGeral(){
		$this->id = '';
		$this->pagina = '';
		$this->ip = "";
		$this->texto = "";
		$this->tipo = "";
		$this->bd = new LogGeralDAO();
	}
	
	public function cadastrar(){
		return $this->bd->cadastrar($this);
	}

	public static function _salvar(
		$p_log = "",
		$p_tipo = ""
	){
		$log = new LogGeral();
		$log->ip = $_SERVER['REMOTE_ADDR'];
		$log->pagina = $_SERVER['REQUEST_URI'];
		$log->texto = $p_log;
		$log->tipo = $p_tipo;
		$log->cadastrar();
	}
}

class LogGeralDAO extends BaseDAO {
	
	public function cadastrar(LogGeral $p){
		try {
			if($this->abreConexao()){
				$str_q = "
					INSERT INTO log_geral( 
						ip, 
						pagina, 
						texto, 
						tipo,
						codprojeto 	
					) VALUES (
						'". $this->con->real_escape_string( $p->ip ) ."'
						,'". $this->con->real_escape_string( $p->pagina ) ."'
						,'". $this->con->real_escape_string( $p->texto ) ."'
						,'". $this->con->real_escape_string( $p->tipo ) ."'
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