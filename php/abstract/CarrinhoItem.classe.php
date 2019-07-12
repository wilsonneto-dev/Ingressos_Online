<?php

//Classe CarrinhoItem e CarrinhoItemDAO
//06 de Maio de 2012

class CarrinhoItem{

	public $id;
	public $cod_usuario;
	public $cod_ingresso;
	public $quantidade;
	public $data_inserido;
	private $bd;
	
	public function CarrinhoItem(){
		$this->id = "";
		$this->cod_ingresso = "";
		$this->cod_usuario = "";
		$this->quantidade = "";
		$this->data_inserido = "";
		$this->bd = new CarrinhoItemDAO();
	}
	
	public function cadastrar(){
		return $this->bd->cadastrar($this);
	}

	public function get(){
		return $this->bd->get($this);
	}

	public function getByUsuarioIngresso(){
		return $this->bd->get($this);
	}

	public function getListaByUsuario(){
		return $this->bd->getListaByUsuario( $this );
	}

	public function atualizar_quantidade(){
		return $this->bd->atualizar_quantidade($this);
	}

	public function atualizar_quantidade_by_usuario_ingresso(){
		return $this->bd->atualizar_quantidade_by_usuario_ingresso($this);
	}

	public function deletar(){
		return $this->bd->deletar($this);
	}

	public function deletarByUsuarioIngresso(){
		return $this->bd->deletarByUsuarioIngresso($this);
	}

	public function deletarByUsuario(){
		return $this->bd->deletarByUsuario($this);
	}

	// estáticos 
	public static function _get( $p_id ){
		$obj = new CarrinhoItem();
		$obj->id = $p_id;
		if ( $obj->get() ) 
			return $obj;
		else 
			return null;
	}

	public static function _getByUsuarioIngresso( $p_cod_usuario, $p_cod_ingresso ){
		$obj = new CarrinhoItem();
		$obj->cod_usuario = $p_cod_usuario;
		$obj->cod_ingresso = $p_cod_ingresso;
		if ( $obj->getByUsuarioIngresso() ) 
			return $obj;
		else 
			return null;
	}
	
	public static function _getListaByUsuario( $p_cod_usuario ){
		$obj = new CarrinhoItem();
		$obj->cod_usuario = $p_cod_usuario;
		return $obj->getListaByUsuario(); 
	}

	public static function _deletar( $p_id ){
		$obj = new CarrinhoItem();
		$obj->id = $p_id;
		if ( $obj->deletar() ) 
			return true;
		else 
			return false;
	}
	
	public static function _deletarByUsuarioIngresso( $p_cod_usuario, $p_cod_ingresso ){
		$obj = new CarrinhoItem();
		$obj->cod_usuario = $p_cod_usuario;
		$obj->cod_ingresso = $p_cod_ingresso;
		if ( $obj->deletarByUsuarioIngresso() ) 
			return true;
		else 
			return false;
	}

	public static function _deletarByUsuario( $p_cod_usuario ){
		$obj = new CarrinhoItem();
		$obj->cod_usuario = $p_cod_usuario;
		if ( $obj->deletarByUsuario() ) 
			return true;
		else 
			return false;
	}

}

class CarrinhoItemDAO extends BaseDAO{
	
	public function cadastrar(CarrinhoItem $p){
		try {
			if($this->abreConexao()){
				$str_q = "
					INSERT INTO 
						carrinho_item( cod_usuario, cod_ingresso, quantidade, codprojeto )
					VALUES('". $this->con->real_escape_string($p->cod_usuario) ."'
						,'". $this->con->real_escape_string($p->cod_ingresso) ."'
						,'". $this->con->real_escape_string($p->quantidade) ."'
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

	public function get($p){
		try {
			if($this->abreConexao()){
				$str_q = "
					SELECT 
						id, 
						cod_usuario, 
						cod_ingresso, 
						quantidade,
						data_inserido
					FROM 
						carrinho_item 
					WHERE 
						codprojeto = " . $this->codProjeto ." 
						AND id = '" . $this->con->real_escape_string( $p->id ) . "';";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->id = $obj->id;
						$p->cod_usuario = $obj->cod_usuario;
						$p->cod_ingresso = $obj->cod_ingresso;
						$p->quantidade = $obj->quantidade;
						if( is_null( $obj->data_inserido) ) $p->data_inserido = null;
						else $p->data_inserido = new DateTime( $obj->data_inserido );
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

	public function getByUsuarioIngresso($p){
		try {
			if($this->abreConexao()){
				$str_q = "
					SELECT 
						id, 
						cod_usuario, 
						cod_ingresso, 
						quantidade,
						data_inserido
					FROM 
						carrinho_item 
					WHERE 
						codprojeto = " . $this->codProjeto ." 
						AND cod_usuario = '" . $this->con->real_escape_string( $p->cod_usuario ) . "'					
						AND cod_ingresso = '" . $this->con->real_escape_string( $p->cod_ingresso ) . "';";					
				if($q = $this->con->query($str_q)){
					if(!$q->num_rows == 0){
						$obj = $q->fetch_object();
						$p->id = $obj->id;
						$p->cod_usuario = $obj->cod_usuario;
						$p->cod_ingresso = $obj->cod_ingresso;
						$p->quantidade = $obj->quantidade;
						if( is_null( $obj->data_inserido) ) $p->data_inserido = null;
						else $p->data_inserido = new DateTime( $obj->data_inserido );
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

	public function getListaByUsuario( $carrinho_item ){
		try {
			if( $this->abreConexao() ){
				$str_q = "
					SELECT 
						id, 
						cod_usuario, 
						cod_ingresso, 
						quantidade,
						data_inserido
					FROM 
						carrinho_item 
					WHERE 
						codprojeto = " . $this->codProjeto ."
						and cod_usuario = '".$this->con->real_escape_string( $carrinho_item->cod_usuario )."';";					
				if($q = $this->con->query($str_q)){
					$lista = array();
					if( $q->num_rows > 0){
						while( ( $obj = $q->fetch_object() ) != false )
						{
							$p = new CarrinhoItem();
							$p->id = $obj->id;
							$p->cod_usuario = $obj->cod_usuario;
							$p->cod_ingresso = $obj->cod_ingresso;
							$p->quantidade = $obj->quantidade;
							if( is_null( $obj->data_inserido) ) $p->data_inserido = null;
							else $p->data_inserido = new DateTime( $obj->data_inserido );
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
			erro_bd( $exception, $this->con->error );
			return false;
		}
	}

	public function atualizar_quantidade( $p ){
		try {
			if($this->abreConexao()){
				$str_q = 
					"UPDATE 
						carrinho_item
					SET 
						quantidade = '". $this->con->real_escape_string($p->quantidade) ."'
					WHERE 
						id = '".$this->con->real_escape_string($p->id)."' 
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
	
	public function atualizar_quantidade_by_usuario_ingresso( $p ){
		try {
			if($this->abreConexao()){
				$str_q = 
					"UPDATE 
						carrinho_item
					SET 
						quantidade = '". $this->con->real_escape_string($p->quantidade) ."'
					WHERE 
						cod_usuario = '" . $this->con->real_escape_string( $p->cod_usuario ) . "'					
						AND cod_ingresso = '" . $this->con->real_escape_string( $p->cod_ingresso ) . "'				
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
	
	public function deletar(CarrinhoItem $obj){
		try {
			if($this->abreConexao()){
				$str_q = "
						DELETE 
							carrinho_item
						WHERE 
							id = '".$this->con->real_escape_string($obj->id)."' 
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


	public function deletarByUsuarioIngresso(CarrinhoItem $obj){
		try {
			if($this->abreConexao()){
				$str_q = "
						DELETE 
							carrinho_item
						WHERE 
							cod_usuario = '" . $this->con->real_escape_string( $p->cod_usuario ) . "'					
							AND cod_ingresso = '" . $this->con->real_escape_string( $p->cod_ingresso ) . "'	
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

	public function deletarByUsuario(CarrinhoItem $obj){
		try {
			if($this->abreConexao()){
				$str_q = "
						DELETE 
							carrinho_item
						WHERE 
							cod_usuario = '" . $this->con->real_escape_string( $p->cod_usuario ) . "'					
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

}

?>