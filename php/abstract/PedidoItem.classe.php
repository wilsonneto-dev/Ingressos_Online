<?php

class PedidoItem{

	public $id;
	public $cod_pedido;
	public $cod_ingresso;
	public $quantidade;
	public $valor_ingresso;
	public $valor_taxa;
	public $valor_total;

	private $bd;
	
	public function PedidoItem(){
		$this->id = '';
		$this->cod_pedido = '';
		$this->cod_ingresso = "";
		$this->quantidade = "";
		$this->valor_ingresso = "";
		$this->valor_total = "";
		$this->valor_taxa = "";
		$this->bd = new PedidoItemDAO();
	}
	
	public function cadastrar(){
		return $this->bd->cadastrar( $this );
	}

	public function get(){
		return $this->bd->get($this);
	}

	public function getListaByPedido(){
		return $this->bd->getListaByPedido( $this );
	}

	// estáticos 
	public static function _get( $p_id ){
		$obj = new PedidoItem();
		$obj->id = $p_id;
		if ( $obj->get() ) 
			return $obj;
		else 
			return null;
	}

	public static function _getListaByPedido( $p_cod_pedido ){
		$obj = new PedidoItem();
		$obj->cod_pedido = $p_cod_pedido;
		return ( $obj->getListaByPedido() ); 
	}



}

class PedidoItemDAO extends BaseDAO{
	
	public function cadastrar(PedidoItem $p){
		try {
			if($this->abreConexao()){
				$str_q = "
					INSERT INTO pedido_item( 
						cod_pedido, 
						cod_ingresso, 
						quantidade,
						valor_ingresso,
						valor_total,
						valor_taxa, 
						codprojeto 
					)VALUES(
						'". $this->con->real_escape_string($p->cod_pedido) ."'
						,'". $this->con->real_escape_string($p->cod_ingresso) ."'
						,'". $this->con->real_escape_string($p->quantidade) ."'
						,'". $this->con->real_escape_string($p->valor_ingresso) ."'
						,'". $this->con->real_escape_string($p->valor_total) ."'
						,'". $this->con->real_escape_string($p->valor_taxa) ."'
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

	public function get($p){
		try {
			if($this->abreConexao()){
				$str_q = "
					SELECT 
						id, 
						cod_pedido, 
						cod_ingresso, 
						quantidade,
						valor_ingresso,
						valor_total,
						valor_taxa 
					FROM 
						pedido_item 
					WHERE 
						codprojeto = " . $this->codProjeto ." 
						AND id = '" . $this->con->real_escape_string( $p->id ) . "';";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->id = $obj->id;
						$p->cod_pedido = $obj->cod_pedido;
						$p->cod_ingresso = $obj->cod_ingresso;
						$p->quantidade = $obj->quantidade;
						$p->valor_ingresso = $obj->valor_ingresso;
						$p->valor_total = $obj->valor_total;
						$p->valor_taxa = $obj->valor_taxa;
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

	public function getListaByPedido($p){
		try {
			if( $this->abreConexao() ){
				$str_q = "
					SELECT 
						id, 
						cod_pedido, 
						cod_ingresso, 
						quantidade,
						valor_ingresso,
						valor_total,
						valor_taxa 
					FROM 
						pedido_item 
					WHERE 
						codprojeto = " . $this->codProjeto ." 
						AND cod_pedido = '" . $this->con->real_escape_string( $p->cod_pedido ) . "';";					
				if($q = $this->con->query($str_q)){
					$lista = array();
					if( $q->num_rows > 0)
					{
						while( ( $obj = $q->fetch_object() ) != false )
						{
							$p = new PedidoItem();
							$p->id = $obj->id;
							$p->cod_pedido = $obj->cod_pedido;
							$p->cod_ingresso = $obj->cod_ingresso;
							$p->quantidade = $obj->quantidade;
							$p->valor_ingresso = $obj->valor_ingresso;
							$p->valor_total = $obj->valor_total;
							$p->valor_taxa = $obj->valor_taxa;
							$lista[] = clone $p;
						}
					}
					return $lista;	
				}
				else
					throw new Exception("erro ao executar query<br />".$str_q);
			}
			else 
				throw new Exception("erro na conexao ao banco");	
		} catch ( Exception $e ) {
			erro_bd( $exception, $this->con->error );
			return false;
		}
	}

}

?>