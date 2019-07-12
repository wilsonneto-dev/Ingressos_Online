<?php

class NotificacaoPagSeguro {

	public $id;
	public $notification_code;
	public $notification_type;
	
	private $bd;
	
	public function NotificacaoPagSeguro(){
		$this->id = '';
		$this->notification_type = '';
		$this->notification_code = "";
		$this->bd = new NotificacaoPagSeguroDAO();
	}
	
	public function cadastrar(){
		return $this->bd->cadastrar($this);
	}

	public static function _salvar(
		$p_notification_code = "",
		$p_notification_type = ""
	){
		$log = new NotificacaoPagSeguro();
		$log->notification_code = $p_notification_code;
		$log->notification_type = $p_notification_type;
		$log->cadastrar();
	}
}

class NotificacaoPagSeguroDAO extends BaseDAO {
	
	public function cadastrar(NotificacaoPagSeguro $p){
		try {
			if($this->abreConexao()){
				$str_q = "
					INSERT INTO notificacao_pagseguro ( 
						notification_code, 
						notification_type
					) VALUES (
						'". $this->con->real_escape_string( $p->notification_code ) ."'
						,'". $this->con->real_escape_string( $p->notification_type ) ."'
					);";
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