<?php

//Classe TempPedidoItem e TempPedidoItemDAO
//06 de Maio de 2012

class TempPedidoItem {

	public $id;
	public $cod_pedido;
	public $codigo;
	public $descricao;
	public $quantidade;
	public $valor_ingresso;
	public $valor_taxa;
	public $valor_total;
	private $bd;
	
	public function TempPedidoItem(){
		$this->id = '';
		$this->cod_pedido = '';
		$this->codigo = "";
		$this->descricao = "";
		$this->quantidade = "";
		$this->valor_ingresso = "";
		$this->valor_taxa = "";
		$this->valor_total = "";
		$this->bd = new TempPedidoItemDAO();
	}
	
	public function cadastrar(){
		return $this->bd->cadastrar($this);
	}

	public function get_by_cod_pedido(){
		return $this->bd->get_by_cod_pedido($this);
	}

	public static function _get_by_cod_pedido( $p_cod_pedido ){
		$obj = new TempPedidoItem();
		$obj->cod_pedido = $p_cod_pedido;
		return ( $obj->get_by_cod_pedido() ); 
	}

}

class TempPedidoItemDAO extends BaseDAO{
	
	public function cadastrar(TempPedidoItem $p){
		try {
			if($this->abreConexao()){
				$str_q = "
					INSERT INTO 
					temp_pedido_item( 
						cod_pedido, 
						codigo, 
						descricao, 
						quantidade, 
						valor_ingresso, 
						valor_taxa, 
						valor_total, 
						codprojeto 
					) VALUES (
						'". $this->con->real_escape_string($p->cod_pedido) ."'
						,'". $this->con->real_escape_string($p->codigo) ."'
						,'". $this->con->real_escape_string($p->descricao) ."'
						,'". $this->con->real_escape_string($p->quantidade) ."'
						,'". $this->con->real_escape_string($p->valor_ingresso) ."'
						,'". $this->con->real_escape_string($p->valor_taxa) ."'
						,'". $this->con->real_escape_string($p->valor_total) ."'
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

	public function get_by_cod_pedido( $p ){
		try {
			if($this->abreConexao()){
				$str_q = "
					SELECT 
						id, 
						cod_pedido, 
						codigo, 
						descricao, 
						quantidade, 
						valor_ingresso, 
						valor_taxa, 
						valor_total, 
						codprojeto 
					FROM 
						temp_pedido_item 
					WHERE 
						ativo = 1
						AND codprojeto = " . $this->codProjeto ." 
						AND cod_pedido = '" . $this->con->real_escape_string( $p->cod_pedido ) . "';";					
				if($q = $this->con->query($str_q)){
					$lista = array();
					if( $q->num_rows > 0)
					{
						while( ( $obj = $q->fetch_object() ) != false )
						{
							$p = new TempPedidoItem();
							$p->id = $obj->id;
							$p->cod_pedido = $obj->cod_pedido;
							$p->codigo = $obj->codigo;
							$p->quantidade = $obj->quantidade;
							$p->descricao = $obj->descricao;
							$p->valor_total = $obj->valor_total;
							$p->valor_taxa = $obj->valor_taxa;
							$lista[] = $p;
						}
					}
					return $lista;	
				}
				else
					throw new Exception( "erro ao executar query<br />".$str_q );
			}
			else 
				throw new Exception("erro na conexao ao banco");	
		} catch (Exception $e) {
			erro_bd( $exception, $this->con->error );
			return false;
		}
	}
	
}

?>