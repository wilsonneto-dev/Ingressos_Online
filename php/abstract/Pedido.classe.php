<?php

class Pedido{

	public $id;
	public $codigo;
	public $data;
	public $cod_usuario;
	public $cod_evento;
	public $ref;
	public $transacao;
	public $data_status;
	public $status;
	public $cod_status;
	public $cancelado;
	public $data_cancelado;
	public $valor_total;
	public $hash;

	public $valor_pedido;
	public $valor_ingressos;
	public $valor_taxa_gateway;
	public $valor_liquido;
	public $valor_total_pago;

	private $bd;
	
	public function Pedido(){
		$this->id = '';
		$this->codigo = '';
		$this->data = "";
		$this->cod_usuario = '';
		$this->cod_evento = '';
		$this->ref = "";
		$this->transacao = "";
		$this->data_status = "";
		$this->status = "";
		$this->cod_status = "";
		$this->cancelado = "";
		$this->data_cancelado = "";
		$this->bd = new PedidoDAO();
	}
	
	public function cadastrar(){
		return $this->bd->cadastrar($this);
	}

	public function get(){
		return $this->bd->get($this);
	}
	
	public function getTotal(){
		return $this->bd->getTotal($this);
	}

	public function getByTransacao(){
		return $this->bd->get($this);
	}

	public function atualizar_infos_gateway(){
		return $this->bd->atualizar_infos_gateway($this);
	}
	
	public function atualizar_status(){
		return $this->bd->atualizar_status($this);
	}

	public function cancelar(){
		return $this->bd->cancelar($this);
	}

	// estáticos 
	public static function _get( $codigo ){
		$obj = new Pedido();
		$obj->codigo = $codigo;
		if ( $obj->get() ) 
			return $obj;
		else 
			return null;
	}

	public static function _getByTransacao( $p_transacao ){
		$obj = new Pedido();
		$obj->transacao = $p_transacao;
		if ( $obj->getByTransacao() ) 
			return $obj;
		else 
			return null;
	}

	public static function _cancelar( $p_id ){
		$obj = new Pedido();
		$obj->id = $p_id;
		return ( $obj->cancelar() );
	}


	public static function _proximo_codigo (  ) {
		$obj = new Pedido();
		$obj->proximo_codigo();
		return $obj->cod_pedido;
	}

	public function proximo_codigo(){
		return $this->bd->proximo_codigo($this);
	}

	public function enviou_ao_gateway(){
		return $this->bd->enviou_ao_gateway($this);
	}



}

class PedidoDAO extends BaseDAO{
	

	public function proximo_codigo(Pedido $p){
		try {
			if($this->abreConexao()){
				$str_q = "select ( ifnull( max(id), 0 ) + 1 ) as proximo_pedido from pedido;";
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->cod_pedido = $obj->proximo_pedido;
						return true;
					}
					else return false;	
				}
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

	public function cadastrar(Pedido $p){
		try {
			if($this->abreConexao()){
				$str_q = "
					INSERT INTO pedido ( 
						codigo, 
						cod_usuario, 
						cod_evento, 
						ref, 
						hash, 
						status, 
						cod_status, 
						transacao, 
						valor_pedido, 
						valor_ingressos, 
						codprojeto
					) VALUES (
						'". $this->con->real_escape_string( $p->codigo ) ."'
						,'". $this->con->real_escape_string( $p->cod_usuario ) ."'
						,'". $this->con->real_escape_string( $p->cod_evento ) ."'
						,'". $this->con->real_escape_string( $p->ref ) ."'
						,'". $this->con->real_escape_string( $p->hash ) ."'
						,'". $this->con->real_escape_string( $p->status ) ."'
						,'". $this->con->real_escape_string( $p->cod_status ) ."'
						,'". $this->con->real_escape_string( $p->transacao ) ."'
						,'". $this->con->real_escape_string( $p->valor_pedido ) ."'
						,'". $this->con->real_escape_string( $p->valor_ingressos ) ."'
						, ". $this->codProjeto.
					");";
				if($q = $this->con->query($str_q)){
					$p->id = $this->con->insert_id;
					return true;
				}
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

	public function get($p){
		try {
			if($this->abreConexao()){
				$str_q = "
					SELECT 
						id, 
						codigo, 
						data, 
						cod_usuario, 
						cod_evento, 
						ref,
						transacao,
						data_status,
						status,
						cod_status,
						hash,
						cancelado,
						data_cancelado,
						valor_ingressos,
						valor_pedido,
						valor_taxa_gateway,
						valor_liquido,
						valor_total_pago
					FROM 
						pedido 
					WHERE 
						codprojeto = " . $this->codProjeto ." 
						AND codigo = '" . $this->con->real_escape_string( $p->codigo ) . "';";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->id = $obj->id;
						$p->codigo = $obj->codigo;
						$p->cod_usuario = $obj->cod_usuario;
						$p->cod_evento = $obj->cod_evento;
						$p->ref = $obj->ref;
						$p->transacao = $obj->transacao;
						$p->status = $obj->status;
						$p->cod_status = $obj->cod_status;
						$p->hash = $obj->hash;
						$p->cancelado = $obj->cancelado;
						
						$p->valor_ingressos = $obj->valor_ingressos;
						$p->valor_pedido = $obj->valor_pedido;
						$p->valor_taxa_gateway = $obj->valor_taxa_gateway;
						$p->valor_liquido = $obj->valor_liquido;
						$p->valor_total_pago = $obj->valor_total_pago;
						
						if( is_null( $obj->data) ) $p->data = null;
						else $p->data = new DateTime( $obj->data );
						if( is_null( $obj->data_status) ) $p->data_status = null;
						else $p->data_status = new DateTime( $obj->data_status );
						if( is_null( $obj->data_cancelado) ) $p->data_cancelado = null;
						else $p->data_cancelado = new DateTime( $obj->data_cancelado );
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

	public function getByTransacao( $p ){
		try {
			if( $this->abreConexao() ){
				$str_q = "
					SELECT 
						id, 
						codigo, 
						data, 
						cod_usuario, 
						cod_evento, 
						ref,
						transacao,
						data_status,
						status,
						cod_status,
						hash,
						cancelado,
						data_cancelado,
						valor_ingressos,
						valor_pedido,
						valor_taxa_gateway,
						valor_liquido,
						valor_total_pago
					FROM 
						pedido 
					WHERE 
						codprojeto = " . $this->codProjeto ." 
						AND transacao = '" . $this->con->real_escape_string( $p->transacao ) . "';";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->id = $obj->id;
						$p->codigo = $obj->codigo;
						$p->cod_usuario = $obj->cod_usuario;
						$p->cod_evento = $obj->cod_evento;
						$p->ref = $obj->ref;
						$p->transacao = $obj->transacao;
						$p->status = $obj->status;
						$p->cod_status = $obj->cod_status;
						$p->hash = $obj->hash;
						$p->cancelado = $obj->cancelado;
						
						$p->valor_ingressos = $obj->valor_ingressos;
						$p->valor_pedido = $obj->valor_pedido;
						$p->valor_taxa_gateway = $obj->valor_taxa_gateway;
						$p->valor_liquido = $obj->valor_liquido;
						$p->valor_total_pago = $obj->valor_total_pago;
						
						if( is_null( $obj->data) ) $p->data = null;
						else $p->data = new DateTime( $obj->data );
						if( is_null( $obj->data_status) ) $p->data_status = null;
						else $p->data_status = new DateTime( $obj->data_status );
						if( is_null( $obj->data_cancelado) ) $p->data_cancelado = null;
						else $p->data_cancelado = new DateTime( $obj->data_cancelado );
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

/*

	public function atualizar_infos_gateway( $p ){
		try {
			if($this->abreConexao()){
				$str_q = 
					"UPDATE 
						pedido
					SET 
						hash = '". $this->con->real_escape_string( $p->hash ) ."'
						, transacao = '". $this->con->real_escape_string( $p->transacao ) ."'
					WHERE 
						id = '".$this->con->real_escape_string( $p->id )."' 
						AND codprojeto = ".$this->codProjeto.";";
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
	
	public function atualizar_status( $p ){
		try {
			if($this->abreConexao()){
				$str_q = 
					"UPDATE 
						pedido
					SET 
						status = '". $this->con->real_escape_string( $p->status ) ."'
						, data_status = CURRENT_TIMESTAMP
					WHERE 
						id = '".$this->con->real_escape_string( $p->id )."' 
						AND codprojeto = ".$this->codProjeto.";";
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
*/


	public function atualizar_infos_gateway( $p )
	{
		try {
			if( $this->abreConexao() ){
				$str_q = 
					"UPDATE 
						pedido
					SET 
						data_status = current_timestamp,
						transacao = '". $this->con->real_escape_string( $p->transacao ) ."',
						status = '". $this->con->real_escape_string( $p->status ) ."',
						cod_status = '". $this->con->real_escape_string( $p->cod_status ) ."',
						valor_taxa_gateway = '". $this->con->real_escape_string( $p->valor_taxa_gateway ) ."',
						valor_liquido = '". $this->con->real_escape_string( $p->valor_liquido ) ."',
						valor_total_pago = '". $this->con->real_escape_string( $p->valor_total_pago ) ."'
					WHERE 
						codigo = '".$this->con->real_escape_string( $p->codigo )."' 
						AND codprojeto = ".$this->codProjeto.";";
				if($q = $this->con->query($str_q))
					return true;
				else
					throw new Exception( "erro ao executar query<br />" . $str_q );
			}
			else 
				throw new Exception("erro na conexao");	
		} catch ( Exception $e ) {
			erro_bd( $e, $this->con->error );
			return false;
		}
	}	
	
	public function atualizar_status($p){
		try {
			if( $this->abreConexao() ){
				$str_q = 
					"UPDATE 
						pedido
					SET 
						cod_status = '". $this->con->real_escape_string($p->cod_status) ."'
						, status = '". $this->con->real_escape_string($p->status) ."'
						, data_status = current_timestamp
					WHERE 
						codigo = '".$this->con->real_escape_string($p->codigo)."' 
						AND codprojeto = ".$this->codProjeto.";";
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

	public function cancelar( $p ){
		try {
			if($this->abreConexao()){
				$str_q = 
					"UPDATE 
						pedido
					SET 
						cancelado = 1
						, data_cancelado = CURRENT_TIMESTAMP
					WHERE 
						id = '".$this->con->real_escape_string( $p->id )."' 
						AND codprojeto = ".$this->codProjeto.";";
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


	public function enviou_ao_gateway( $p )
	{
		try {
			if( $this->abreConexao() ){
				$str_q = 
					"UPDATE 
						pedido
					SET 
						enviou_ao_gateway = 1
					WHERE 
						codigo = '".$this->con->real_escape_string( $p->codigo )."' 
						AND codprojeto = ".$this->codProjeto.";";
				if($q = $this->con->query($str_q))
					return true;
				else
					throw new Exception( "erro ao executar query<br />" . $str_q );
			}
			else 
				throw new Exception("erro na conexao");	
		} catch ( Exception $e ) {
			erro_bd( $e, $this->con->error );
			return false;
		}
	}	
	
}

?>