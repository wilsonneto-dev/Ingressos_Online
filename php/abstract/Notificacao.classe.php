<?php

class Notificacao {

	public $id;
	public $cod_notificacao_tipo;
	public $cod_complemento;
	public $link;
	public $icone;
	public $texto;
	public $data;

	private $bd;
	
	public function Notificacao(){
		$this->id = '';
		$this->cod_complemento = '';
		$this->cod_notificacao_tipo = "";
		$this->link = "";
		$this->icone = "";
		$this->texto = "";
		$this->data = "";
		$this->bd = new NotificacaoDAO();
	}

	public function cadastrar(){
		return $this->bd->cadastrar($this);
	}

	public function getListaByData(  ){
		return $this->bd->getListaByData($this);
	}

	public static function _getListaByData( $p_data, $p_tipo ){
		$obj = new Notificacao();
		$obj->data = $p_data;
		$obj->cod_notificacao_tipo = $p_tipo;
		return $obj->getListaByData();
	}

}

class NotificacaoDAO extends BaseDAO {
	
	public function cadastrar(Notificacao $p){
		try {
			if($this->abreConexao()){
				$str_q = "
					INSERT INTO notificacao ( 
						cod_notificacao_tipo, 
						cod_complemento, 
						link, 
						icone, 
						texto, 
						codprojeto 	
					) VALUES (
						'". $this->con->real_escape_string( $p->cod_notificacao_tipo ) ."'
						,'". $this->con->real_escape_string( $p->cod_complemento ) ."'
						,'". $this->con->real_escape_string( $p->link ) ."'
						,'". $this->con->real_escape_string( $p->icone ) ."'
						,'". $this->con->real_escape_string( $p->texto ) ."'
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

	public function getListaByData( $p_obj ){
		try {
			if($this->abreConexao()){
				$str_q = "
				SELECT 	
					id,
					cod_notificacao_tipo, 
					cod_complemento, 
					link, 
					icone, 
					texto,
					data 
				FROM
					notificacao
				WHERE
					data > str_to_date( '". $this->con->real_escape_string( $p_obj->data->format('d/m/Y') ) ."', '%d/%m/%Y' )
					AND cod_notificacao_tipo = '" . $p_obj->cod_notificacao_tipo . "'
					AND ativo = 1
					AND codprojeto = " . $this->codProjeto ." 
				ORDER BY 
					data DESC";
				if( $q = $this->con->query( $str_q ) ){
					$lista = array();
					if( $q->num_rows > 0 ){
						while( ( $obj = $q->fetch_object() ) != false )
						{
							$p = new Notificacao();
							$p->id = $obj->id;
							$p->cod_notificacao_tipo = $obj->cod_notificacao_tipo;
							$p->cod_complemento = $obj->cod_complemento;
							$p->link = $obj->link;
							$p->icone = $obj->icone;
							$p->texto = $obj->texto;
							$p->data = new DateTime( $obj->data );
							$lista[] = $p;
						}
					}
					return $lista;
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