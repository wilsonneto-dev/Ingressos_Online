<?php

class LogUsuario {

	public $id;
	public $ip;
	public $pagina;
	public $texto;
	public $tipo;
	public $cod_usuario;

	private $bd;
	
	public function LogUsuario(){
		$this->id = '';
		$this->pagina = '';
		$this->ip = "";
		$this->texto = "";
		$this->tipo = "";
		$this->cod_usuario = "";
		$this->bd = new LogUsuarioDAO();
	}
	
	public function cadastrar(){
		return $this->bd->cadastrar($this);
	}

	public static function _salvar(
		$p_log = "",
		$p_tipo = "",
		$p_cod_usuario = ""
	){
		$log = new LogUsuario();
		$log->ip = $_SERVER['REMOTE_ADDR'];
		$log->pagina = $_SERVER['REQUEST_URI'];
		$log->texto = $p_log;
		$log->tipo = $p_tipo;
		$log->cod_usuario = $p_cod_usuario;
		$log->cadastrar();
	}
}

class LogUsuarioDAO extends BaseDAO {
	
	public function cadastrar(LogUsuario $p){
		try {
			if($this->abreConexao()){
				$str_q = "
					INSERT INTO log_usuario( 
						ip, 
						pagina, 
						texto, 
						tipo, 
						cod_usuario, 
						codprojeto 	
					) VALUES (
						'". $this->con->real_escape_string( $p->ip ) ."'
						,'". $this->con->real_escape_string( $p->pagina ) ."'
						,'". $this->con->real_escape_string( $p->texto ) ."'
						,'". $this->con->real_escape_string( $p->tipo ) ."'
						,'". $this->con->real_escape_string( $p->cod_usuario ) ."'
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