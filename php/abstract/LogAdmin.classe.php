<?php

class LogAdmin {

	public $id;
	public $ip;
	public $pagina;
	public $texto;
	public $tipo;
	public $valor_anterior;
	public $valor_apos;
	public $cod_admin;

	private $bd;
	
	public function LogAdmin(){
		$this->id = '';
		$this->pagina = '';
		$this->ip = "";
		$this->texto = "";
		$this->tipo = "";
		$this->valor_anterior = "";
		$this->valor_apos = "";
		$this->cod_admin = "";
		$this->bd = new LogAdminDAO();
	}
	
	public function cadastrar(){
		return $this->bd->cadastrar($this);
	}

	public static function _salvar(
		$p_log = "",
		$p_tipo = "",
		$p_cod_admin = "",
		$p_valor_anterior = "",
		$p_valor_apos = ""
	){
		$log = new LogAdmin();
		$log->ip = $_SERVER['REMOTE_ADDR'];
		$log->pagina = $_SERVER['REQUEST_URI'];
		$log->texto = $p_log;
		$log->tipo = $p_tipo;
		$log->valor_anterior = $p_valor_anterior;
		$log->valor_apos = $p_valor_apos;
		$log->cod_admin = $p_cod_admin;
		$log->cadastrar();
	}
}

class LogAdminDAO extends BaseDAO {
	
	public function cadastrar(LogAdmin $p){
		try {
			if($this->abreConexao()){
				$str_q = "
					INSERT INTO log_admin( 
						ip, 
						pagina, 
						texto, 
						tipo, 
						valor_anterior, 
						valor_apos, 
						cod_admin, 
						codprojeto 	
					) VALUES (
						'". $this->con->real_escape_string( $p->ip ) ."'
						,'". $this->con->real_escape_string( $p->pagina ) ."'
						,'". $this->con->real_escape_string( $p->texto ) ."'
						,'". $this->con->real_escape_string( $p->tipo ) ."'
						,'". $this->con->real_escape_string( $p->valor_anterior ) ."'
						,'". $this->con->real_escape_string( $p->valor_apos ) ."'
						,'". $this->con->real_escape_string( $p->cod_admin ) ."'
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