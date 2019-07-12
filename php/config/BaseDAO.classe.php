<?php
class BaseDAO{
/*
 * classe com as configurações do banco
 * as classes que usam acesso ao banco devem ser derivadas desta
*/
	public static $SomenteLeitura = false;
	
	public $codProjeto = CODPROJETO;

	protected $base = "zedoingr_oficial";
	protected $usuario = "root";
	protected $pass = "";
	protected $host = "localhost";

	protected $leitura_CodProjeto = CODPROJETO;
	protected $leitura_base = "zedoingr_oficial";
	protected $leitura_usuario = "root";
	protected $leitura_pass = "";
	protected $leitura_host = "localhost";
	
	/*configurações do banco*/
	public function BaseDAO(){}
	
	/*controla conexão com o banco*/
	protected function AbreConexao(){
		try {
			if( BaseDAO::$SomenteLeitura == false )
				$this->con = new mysqli( $this->host, $this->usuario, $this->pass, $this->base );		
			else
				$this->con = new mysqli( $this->leitura_host, $this->leitura_usuario, $this->leitura_pass, $this->leitura_base );		
			if( $this->con ){
				$this->con->set_charset("utf8");
				return true;
			} else
				return false;
		}
		catch (Exception $e) {
			return false;
		}
	}

	protected function FechaConexao(){
		try {
			return $this->con->close();
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function get_mysqli(){
		return new mysqli( $this->host, $this->usuario, $this->pass, $this->base );
	}
	
	public static function _get_mysqli(){
		$bd = new BaseDAO();
		return $bd->get_mysqli();
	}

}


?>